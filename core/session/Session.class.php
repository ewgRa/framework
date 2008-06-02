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
		}
		
		public static function get($alias)
		{
			return self::me()->getRealization()->get($alias);
		}

		public static function set($alias, $value)
		{
			return self::me()->getRealization()->set($alias, $value);
		}
	}
?>