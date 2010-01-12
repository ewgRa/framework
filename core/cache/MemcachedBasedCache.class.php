<?php
	/* $Id$ */
	
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
		
		/**
		 * @return Memcache
		 */
		public function getMemcache()
		{
			if (!$this->memcache) {
				$this->memcache = new Memcache();
				$this->memcache->addServer($this->getHost(), $this->getPort());
			}
			
			return $this->memcache;
		}
		
		public function get(CacheTicket $ticket)
		{
			if ($this->isDisabled()) {
				$ticket->expired();
				return null;
			}
			
			$actualTime = $ticket->getActualTime();

			if (!$actualTime)
				$actualTime = time();
			
			$result = null;
			
			$key = $this->compileKey($ticket);
			
			if ($data = $this->getMemcache()->get($key)) {
				$ticket->setExpiredTime($data['lifeTime']);
				
				if ($data['lifeTime'] && $data['lifeTime'] < $actualTime) {
					$this->dropByKey($key);
					$ticket->expired();
				} else {
					$ticket->actual();
					$result = $data['data'];
				}
			} else
				$ticket->setExpiredTime(null);

			if (Singleton::hasInstance('Debug') && Debug::me()->isEnabled())
				$this->debug($ticket);
		
			return $result;
		}

		/**
		 * @return FileBasedCache
		 */
		public function set(CacheTicket $ticket)
		{
			if ($this->isDisabled())
				return null;

			$key = $this->compileKey($ticket);
				
			$data = array(
				'data' 		=> $ticket->getData(),
				'lifeTime' 	=> $ticket->getLifeTime()
			);
			
			$lifeTime = $ticket->getLifeTime();
			$ticket->setExpiredTime($lifeTime);
			
			if ($lifeTime <= time())
				$lifeTime = null;
			
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
	}
?>