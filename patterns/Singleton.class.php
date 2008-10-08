<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * @example ../tests/patterns/SingletonTest.class.php
 	 */
	class Singleton
	{
		private static $instances = array();
		
		protected function __construct()
		{
		}
		
		public static function getInstance($className)
		{
			if(!self::hasInstance($className))
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
		
		public static function me()
		{
		}
	}
?>