<?php
	/* $Id$ */

	class DatabaseTest extends UnitTestCase
	{
		public function testIsSingleton()
		{
			Database::factory('MysqlDatabase');
			
			$this->assertTrue(Database::me() instanceof Singleton);
		}
	}
?>