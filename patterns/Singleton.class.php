<?php
	/* $Id$ */

	/**
	 * Singletone pattern
	 * @example
			class MySingleton extends Singleton
			{
				public static function me()
				{
					return parent::getInstance(__CLASS__);
				}
			}
	 */
	class Singleton
	{
		public static $instances = array();
		
		protected function __construct()
		{
			
		}
		
		public static function getInstance($className)
		{
			if(!isset(self::$instances[$className]))
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
		
		public static function dropInstance($className)
		{
			unset(self::$instances[$className]);

			return null;
		}
		
		public static function me()
		{
			
		}
	}
?>