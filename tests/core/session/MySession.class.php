<?php
	class MySession extends Session
	{
		public static function dropInstance()
		{
			self::$instance = null;
		}

		public static function setInstance($instance)
		{
			self::$instance = $instance;
		}
	}
?>