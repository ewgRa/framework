<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class TestSingleton extends Singleton
	{
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public static function getInstance($className)
		{
			$function = __FUNCTION__;
			return parent::$function($className);
		}

		public static function createInstance($className)
		{
			$function = __FUNCTION__;
			return parent::$function($className);
		}

		public static function setInstance($className, $instance)
		{
			$function = __FUNCTION__;
			return parent::$function($className, $instance);
		}

		public static function hasInstance($className)
		{
			$function = __FUNCTION__;
			return parent::$function($className);
		}

		public static function dropInstance($className)
		{
			$function = __FUNCTION__;
			return parent::$function($className);
		}
	}
?>