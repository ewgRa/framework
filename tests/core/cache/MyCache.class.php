<?php
	class MyCache extends Cache 
	{
		protected static $instance = null;
		
		public static function me()
		{
			$funcArgs = func_get_args();
			return parent::getInstance(__CLASS__, $funcArgs, self::$instance);
		}
		
		function dropInstance()
		{
			$this->instance = null;
		}

		public static function get($key, $prefix = null)
		{
			return self::me()->getConnector()->getData($key, $prefix);
		}
		
		public static function set(
			$data, $lifeTime = null,
			$key = null, $prefix = null
		)
		{
			return self::me()->getConnector()->setData($data, $lifeTime, $key, $prefix);
		}
	}
?>