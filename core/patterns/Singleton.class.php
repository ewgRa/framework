<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class Singleton implements SingletonInterface
	{
		private static $instances = array();

		/**
		 * @return Singleton
		 */
		protected function __construct()
		{
		}

		protected static function getInstance($className)
		{
			if (!self::hasInstance($className))
				self::$instances[$className] = self::createInstance($className);

			return self::$instances[$className];
		}

		protected static function setInstance($className, $instance)
		{
			self::$instances[$className] = $instance;

			return $instance;
		}

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