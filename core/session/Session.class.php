<?php
	class Session extends Singleton
	{
		private $realization = null;
		
		/**
		 * @return Session
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function setRealization($realization)
		{
			$this->realization = $realization;
			return $this;
		}
		
		public function getRealization()
		{
			return $this->realization;
		}

		public function relativeStart()
		{
			$this->getRealization()->relativeStart();
			return $this;
		}
		
		public function isStarted()
		{
			return $this->getRealization()->isStarted();
		}
		
		public function get($alias)
		{
			return $this->getRealization()->get($alias);
		}

		public function set($alias, $value)
		{
			return $this->getRealization()->set($alias, $value);
		}
		
		public function drop($alias)
		{
			return $this->getRealization()->drop($alias);
		}
		
		public function start()
		{
			return $this->getRealization()->start();
		}

		public function save()
		{
			return $this->getRealization()->save();
		}
		
		public function getCookie($alias)
		{
			$result = null;
			
			if(isset($_COOKIE[$alias]))
				$result = $_COOKIE[$alias];
			
			return $result;
		}
	}
?>