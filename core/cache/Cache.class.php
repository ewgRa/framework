<?php
	class Cache extends Singleton
	{
		private $realization = null;
		
		protected static $instance = null;

		/**
		 * @return Cache
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
		
		public static function get($key, $prefix = null)
		{
			return self::me()->getRealization()->getData($key, $prefix);
		}
		
		public static function set(
			$data, $lifeTillTime = null,
			$key = null, $prefix = null
		)
		{
			return self::me()->getRealization()->setData($data, $lifeTillTime, $key, $prefix);
		}
		
		public static function setActual($time)
		{
			return self::me()->getRealization()->setActualTime($time);
		}		
	}
?>