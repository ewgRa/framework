<?php
	abstract class CacheTicket
	{
		private $cacheInstance = null;
		private $data = null;
		private $prefix = null;
		private $key = null;
		private $actualTime = null;
		private $isExpired 			= true;
		private $lifeTime 	= null;
		
		/**
		 * @return CacheTicket
		 */
		public static function create()
		{
			return new self;
		}
		
		public function __construct()
		{
			$this->setLifeTime(31536000 + time()); # one year
		}
		
		public function setCacheInstance($instance)
		{
			$this->cacheInstance = $instance;
			return $this;
		}
		
		public function getCacheInstance()
		{
			return $this->cacheInstance;
		}
		
		public function getPrefix()
		{
			return $this->prefix;
		}
		
		public function setPrefix($prefix)
		{
			$this->prefix = $prefix;
			return $this;
		}
		
		public function getKey()
		{
			return $this->key;
		}
		
		public function setKey()
		{
			$this->key = func_get_args();
			return $this;
		}
		
		public function getActualTime()
		{
			return $this->actualTime;
		}
		
		public function setActualTime($actualTime)
		{
			$this->actualTime = $actualTime;
			return $this;
		}
		
		public function isExpired()
		{
			return $this->isExpired;
		}

		public function getLifeTime()
		{
			return $this->lifeTime;
		}
		
		public function setLifeTime($lifeTime)
		{
			$this->lifeTime = $lifeTime;
			return $this;
		}
		
		public function expired()
		{
			$this->isExpired = true;
			return $this;
		}
		
		public function actual()
		{
			$this->isExpired = false;
			return $this;
		}
		
		public function getData()
		{
			return $this->data;
		}
		
		public function setData($data)
		{
			$this->data = $data;
			return $this;
		}
		
		abstract public function storeData();
		abstract public function restoreData();
	}
?>