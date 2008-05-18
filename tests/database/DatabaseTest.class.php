<?php
	// FIXME: i'm bad test
	
	include_once FRAMEWORK_DIR . '/exceptions/database.exception.php';	
	include_once FRAMEWORK_DIR . '/database/mysql.db.php';	
	include_once FRAMEWORK_DIR . '/database/db.php';	
	include_once FRAMEWORK_DIR . '/patterns/singleton.php';	
	
	class MyDB extends DB
	{
		public function __construct()
		{
			
		}
		
		/**
		 * @return MyDB
		 */
		public static function me()
		{
			$funcArgs = func_get_args();
			return Singleton::getInstance(__CLASS__, $funcArgs, self::$instance);
		}
	}
	
	class DatabaseTest extends UnitTestCase 
	{
		function testIsSingleton()
		{
			$this->assertTrue(DB::me() instanceof Singleton);
		}
	}
?>