<?php
	/* $Id$ */

	class DatabaseTest extends UnitTestCase
	{
		private $savedDatabase = null;
				
		public function setUp()
		{
			if(Singleton::hasInstance('Database'))
				$this->savedDatabase = serialize(Database::me());
		}
		
		public function tearDown()
		{
			if($this->savedDatabase)
			{
				Singleton::setInstance(
					'Database',
					unserialize($this->savedDatabase)
				);
			}
			else
				Singleton::dropInstance('Database');
		}
		
		public function testIsSingleton()
		{
			$class = new ReflectionClass('Database');
			
			$this->assertTrue($class->isSubclassOf('Singleton'));
		}
	}
?>