<?php
	/* $Id$ */

	class DatabaseTest extends UnitTestCase
	{
		public function testIsSingleton()
		{
			DatabaseFactory::factory('MysqlDatabase');
			
			$this->assertTrue(Database::me() instanceof Singleton);
		}
	}
?>