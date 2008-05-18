<?php
	class MyTestConfig extends Config
	{
		private static $instance = null;
		
		/**
		 * @return MyConfigTest
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__, self::$instance);
		}
		
		public function setInstance($instance)
		{
			self::$instance = $instance;
		}

		public function replaceVariables($variables)
		{
			return parent::replaceVariables($variables);
		}

		public function registerConstants()
		{
			return parent::registerConstants();
		}
	}
?>