<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class CacheTicket
	{
		/**
		 * @var CacheInterface
		 */
		private $cacheInstance = null;

		private $prefix 	= null;
		private $key 		= null;
		
		private $lifeTime 	= null; //seconds
		
		private $expired	= true;
		private $expiredTime = null;
		
		/**
		 * @return CacheTicket
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function setCacheInstance(CacheInterface $instance)
		{
			$this->cacheInstance = $instance;
			return $this;
		}
		
		/**
		 * @return CacheInterface
		 */
		public function getCacheInstance()
		{
			return $this->cacheInstance;
		}
		
		public function getPrefix()
		{
			return $this->prefix;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function setPrefix($prefix)
		{
			$this->prefix = $prefix;
			return $this;
		}
		
		public function getKey()
		{
			return $this->key;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function setKey(/* $argument1, $argument2, ..., $argumentN */)
		{
			$this->key = func_get_args();
			return $this;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function addKey(/* $argument1, $argument2, ..., $argumentN */)
		{
			$this->key = array($this->key, func_get_args());
			return $this;
		}
		
		public function getExpiredTime()
		{
			return $this->expiredTime;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function setExpiredTime($expiredTime)
		{
			$this->expiredTime = $expiredTime;
			return $this;
		}
		
		public function isExpired()
		{
			return $this->expired;
		}

		public function getLifeTime()
		{
			return $this->lifeTime;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function setLifeTime($lifeTime)
		{
			$this->lifeTime = $lifeTime;
			return $this;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function expired()
		{
			$this->expired = true;
			return $this;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function actual()
		{
			$this->expired = false;
			return $this;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function storeData($data)
		{
			$this->getCacheInstance()->set($this, $data);
			return $this;
		}

		public function restoreData()
		{
			return $this->getCacheInstance()->get($this);
		}
		
		public function drop()
		{
			$this->getCacheInstance()->drop($this);
			return $this;
		}
	}
?>