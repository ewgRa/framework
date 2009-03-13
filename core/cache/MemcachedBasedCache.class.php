<?php
	/* $Id$ */
	
	$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'BaseCache.class.php';
	
	if(!class_exists('BaseCache',false) && file_exists($file))
		require_once($file);
		
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MemcachedBasedCache extends BaseCache
	{
		private $host = null;
		private $port = null;

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
			if(!$this->memcache)
			{
				$this->memcache = new Memcache();
				$this->memcache->addServer($this->getHost(), $this->getPort());
			}
			
			return $this->memcache;
		}
		
		public function get(CacheTicket $ticket)
		{
			if($this->isDisabled())
			{
				$ticket->expired();
				return null;
			}
			
			$actualTime = $ticket->getActualTime();

			if(!$actualTime)
				$actualTime = time();
			
			$result = null;
			
			$key = $this->compileKey(
				$ticket->getKey(), $ticket->getPrefix()
			);
			
			if($data = $this->getMemcache()->get($key))
			{
				if($data['lifeTime'] && $data['lifeTime'] < $actualTime)
				{
					$this->getMemcache()->delete($key);
					$ticket->expired();
				}
				else
				{
					$ticket->actual();
					$result = $data['data'];
				}
			}

			return $result;
		}

		/**
		 * @return FileBasedCache
		 */
		public function set(CacheTicket $ticket)
		{
			if($this->isDisabled())
				return null;

			$key = $this->compileKey(
				$ticket->getKey(), $ticket->getPrefix()
			);
				
			if(!$key)
				throw ExceptionsMapper::me()->createException('DefaultException')->
						setMessage('no key');
			
			$data = array(
				'data' 		=> $ticket->getData(),
				'lifeTime' 	=> $ticket->getLifeTime()
			);
			
			$lifeTime = $ticket->getLifeTime();
			
			if($lifeTime <= time())
				$lifeTime = null;
			
			$this->getMemcache()->set($key, $data, $lifeTime);
			
			return $this;
		}

		private function compileKey($key, $prefix)
		{
			return $prefix . '-' . md5(serialize($key));
		}
	}
?>