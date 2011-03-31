<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MemcachedBasedCache extends BaseCache
	{
		private $servers = array(
			array(
				'host' => 'localhost',
				'port' => 11211
			)
		);

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
		public function addServer($host, $port)
		{
			$this->servers[] = array(
				'host' => $host,
				'port' => $port
			);
			
			return $this;
		}
		
		/**
		 * @return MemcachedBasedCache
		 */
		public function dropServers()
		{
			$this->servers = array();
			
			return $this;
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

			$this->notifyObservers(
				self::GET_TICKET_EVENT,
				Model::create()->
				set('ticket', $ticket)
			);
					
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
			# NOTE: zero needed for back compability with memcached protocol
			$this->getMemcache()->delete($key, 0);
			return $this;
		}
		
		/**
		 * @return MemcachedBasedCache
		 */
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
				$this->memcache = new \Memcache();
				
				foreach ($this->servers as $server)
					$this->memcache->addServer($server['host'], $server['port']);
			}
			
			return $this->memcache;
		}
	}
?>