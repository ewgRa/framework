<?php
	class DatabaseTest extends UnitTestCase
	{
		public function testIsSingleton()
		{
			$this->assertTrue(Database::me() instanceof Singleton);
		}
	}
?>