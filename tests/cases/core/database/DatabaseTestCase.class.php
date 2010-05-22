<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseTestCase extends FrameworkTestCase
	{
		private $savedDatabase = null;
		
		public function setUp()
		{
			$this->savedDatabase = serialize(Database::me());
			Singleton::dropInstance('Database');
		}
		
		public function tearDown()
		{
			Singleton::setInstance(
				'Database',
				unserialize($this->savedDatabase)
			);
		}
		
		public function testIsSingleton()
		{
			$this->assertTrue(Database::me() instanceof Singleton);
		}
		
		public function testPoolOperations()
		{
			$this->assertFalse(Database::me()->hasPool('default'));
			$this->assertFalse(Database::me()->hasPool('default2'));
			
			try {
				Database::me()->getPool('default2');
				$this->fail();
			} catch (MissingArgumentException $e) {
				# good
			}
			
			$pool = MysqlDatabase::create();
			
			Database::me()->addPool($pool, 'default');
			Database::me()->addPool(MysqlDatabase::create(), 'default2');
			
			$this->assertTrue(Database::me()->hasPool('default'));
			$this->assertTrue(Database::me()->hasPool('default2'));
			
			$this->assertSame(Database::me()->getPool('default'), $pool);
		}
	}
?>