<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MemcachedBasedCache extends BaseCache
	{
		private $host = 'localhost';
		private $port = 11211;

		private $memcache = null;
		
		/**
		 * @return MemcachedBasedCache
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return MemcachedBasedCache
		 */
		public function setHost($host)
		{
			$this->host = $host;
			return $this;
		}
		
		public function getHost()
		{
			return $this->host;
		}
		
		/**
		 * @return MemcachedBasedCache
		 */
		public function setPort($port)
		{
			$this->port = $port;
			return $this;
		}
		
		public function getPort()
		{
			return $this->port;
		}
		
		public function get(CacheTicket $ticket)
		{
			$result = null;
			
			$key = $this->compileKey($ticket);
			
			if ($data = $this->getMemcache()->get($key)) {
				
				$ticket->
					setExpiredTime($data['lifeTime'])->
					actual();
					
				$result = $data['data'];
			} else {
				$ticket->setExpiredTime(null);
				$ticket->expired();
			}

			$this->debug($ticket);
		
			return $result;
		}

		/**
		 * @return FileBasedCache
		 */
		public function set(CacheTicket $ticket, $data)
		{
			$lifeTime = $ticket->getLifeTime();

			if (is_null($lifeTime))
				$lifeTime = Cache::FOREVER;
						
			$lifeTime += time();
			
			Assert::isTrue($lifeTime > time());

			$key = $this->compileKey($ticket);
				
			$data = array(
				'data' 		=> $data,
				'lifeTime' 	=> $lifeTime
			);
			
			$ticket->setExpiredTime($lifeTime);
			
			$this->getMemcache()->set($key, $data, 0, $lifeTime);
			
			return $this;
		}

		/**
		 * @return MemcachedBasedCache
		 */
		public function dropByKey($key)
		{
			$this->getMemcache()->delete($key);
			return $this;
		}
		
		public function compileKey(CacheTicket $ticket)
		{
			return
				$this->getNamespace().'-'.$ticket->getPrefix()
				.'-'.md5(serialize($ticket->getKey()));
		}
		
		public function clean()
		{
			$this->getMemcache()->flush();
			return $this;
		}

		/**
		 * @return Memcache
		 */
		private function getMemcache()
		{
			if (!$this->memcache) {
				$this->memcache = new Memcache();
				$this->memcache->addServer($this->getHost(), $this->getPort());
			}
			
			return $this->memcache;
		}
	}
?>