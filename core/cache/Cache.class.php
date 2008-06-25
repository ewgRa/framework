<?php
	abstract class Cache extends Singleton
	{
		private $isDisabled			= false;
		private $isExpired 			= true;
		private $defaultLifeTime 	= 31536000; #one year
		
		/**
		 * @return Cache
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public static function factory($realization)
		{
			 return parent::setInstance(__CLASS__, $realization);
		}
		
		public function disable()
		{
			$this->isDisabled = true;
			return $this;
		}
		
		public function enable()
		{
			$this->isDisabled = false;
			return $this;
		}
		
		public function isDisabled()
		{
			return $this->isDisabled;
		}
		
		public function isExpired()
		{
			return $this->isExpired;
		}

		public function getDefaultLifeTime()
		{
			return $this->defaultLifeTime;
		}
		
		public function setDefaultLifeTime($defaultLifeTime)
		{
			$this->defaultLifeTime = $defaultLifeTime;
			return $this;
		}
		
		protected function expired()
		{
			$this->isExpired = true;
			return $this;
		}
		
		protected function actual()
		{
			$this->isExpired = false;
			return $this;
		}
		
		abstract public function get($key, $prefix = null, $actualTime = null);
		
		abstract public function set(
			$data, $lifeTillTime = null, $key = null, $prefix = null
		);
	}
?>