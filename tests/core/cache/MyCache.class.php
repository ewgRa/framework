<?php
	class MyCache extends Cache
	{
		public static function dropInstance()
		{
			self::$instance = null;
		}

		public static function setInstance($instance)
		{
			self::$instance = $instance;
		}

		public static function drop()
		{
			self::$instance = null;
		}
	}
?>