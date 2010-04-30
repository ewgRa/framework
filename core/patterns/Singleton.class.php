<?php
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
		
		public static function getInstance($className)
		{
			if (!self::hasInstance($className))
				self::$instances[$className] = self::createInstance($className);

			return self::$instances[$className];
		}
		
		public static function createInstance($className)
		{
			self::$instances[$className] = new $className;
			
			return self::$instances[$className];
		}
		
		public static function setInstance($className, $instance)
		{
			self::$instances[$className] = $instance;
			
			return $instance;
		}
		
		public static function hasInstance($className)
		{
			return isset(self::$instances[$className]);
		}
		
		public static function dropInstance($className)
		{
			unset(self::$instances[$className]);
		}
	}
?>