<?php
	/**
	 * Singletone pattern
	 * @example
			class MySingleton extends Singleton
			{
				private static $instance = null;

				public static function me()
				{
					return parent::getInstance(__CLASS__, self::$instance);
				}
			}
	 */
	class Singleton
	{
		protected function __construct()
		{
			
		}
		
		public static function getInstance(
			$className,
			&$instance
		)
		{
			if(!$instance)
			{
				$instance = new $className;
			}

			return $instance;
		}
		
		public static function me()
		{
			
		}
	}
?>