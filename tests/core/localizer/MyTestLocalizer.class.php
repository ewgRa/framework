<?php
	class MyTestLocalizer extends Localizer
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