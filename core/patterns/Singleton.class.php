<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class Singleton implements SingletonInterface
	{
		private static $instances = array();

		public static function me()
		{
			return self::getInstance(get_called_class());
		}

		/**
		 * @return Singleton
		 */
		protected function __construct()
		{
		}

		/**
		 * @access private
		 * protected because needed for tests
		 * actually is private, use it carefully on your own risk
		 */
		protected static function getInstance($className)
		{
			if (!self::hasInstance($className))
				self::$instances[$className] = self::createInstance($className);

			return self::$instances[$className];
		}

		/**
		 * @access private
		 * protected because needed for tests
		 * actually is private, use it carefully on your own risk
		 */
		protected static function setInstance($className, $instance)
		{
			self::$instances[$className] = $instance;

			return $instance;
		}

		/**
		 * @access private
		 * protected because needed for tests
		 * actually is private, use it carefully on your own risk
		 */
		protected static function dropInstance($className)
		{
			unset(self::$instances[$className]);
		}

		private static function createInstance($className)
		{
			self::$instances[$className] = new $className;

			return self::$instances[$className];
		}

		private static function hasInstance($className)
		{
			return isset(self::$instances[$className]);
		}
	}
?>