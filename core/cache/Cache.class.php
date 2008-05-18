<?php
	class Cache extends Singleton
	{
		private $connector = null;
		
		protected static $instance = null;

		/**
		 * @return Cache
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__, self::$instance);
		}
		
		public function setConnector($connector)
		{
			$this->connector = $connector;
			return $this;
		}
		
		public function getConnector()
		{
			return $this->connector;
		}
		
		public static function get($key, $prefix = null)
		{
			return self::me()->getConnector()->getData($key, $prefix);
		}
		
		public static function set(
			$data, $lifeTillTime = null,
			$key = null, $prefix = null
		)
		{
			return self::me()->getConnector()->setData($data, $lifeTillTime, $key, $prefix);
		}
		
		public static function setActual($time)
		{
			return self::me()->getConnector()->setActualTime($time);
		}		
	}
?>