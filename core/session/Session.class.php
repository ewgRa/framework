<?php
	// FIXME: tested?
	class Session extends Singleton
	{
		private $realization = null;
		
		protected static $instance = null;

		/**
		 * @return Session
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__, self::$instance);
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
			return self::me()->getRealization()->isStarted();
		}
		
		public static function get($alias)
		{
			return self::me()->getRealization()->get($alias);
		}

		public static function set($alias, $value)
		{
			return self::me()->getRealization()->set($alias, $value);
		}
		
		public static function drop($alias)
		{
			return self::me()->getRealization()->drop($alias);
		}
		
		public static function start()
		{
			return self::me()->getRealization()->start();
		}

		public static function save()
		{
			return self::me()->getRealization()->save();
		}
		
		public static function getCookie($alias)
		{
			$result = null;
			
			if(isset($_COOKIE[$alias]))
				$result = $_COOKIE[$alias];
			
			return $result;
		}
	}
?>