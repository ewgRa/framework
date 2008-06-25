<?php
	abstract class Session extends Singleton
	{
		protected $isStarted = false;

		/**
		 * @return Session
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public static function factory($realization)
		{
			 return parent::setInstance(__CLASS__, $realization);
		}
		
		public function getCookie($alias)
		{
			$result = null;
			
			if(isset($_COOKIE[$alias]))
				$result = $_COOKIE[$alias];
			
			return $result;
		}

		public function isStarted()
		{
			return $this->isStarted;
		}
		
		abstract public function relativeStart();
		abstract public function start();
		abstract public function save();
		abstract public function getId();
		abstract public function get($alias);
		abstract public function set($alias, $value);
		abstract public function drop($alias);
	}
?>