<?php
	namespace ewgraFramework\tests;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseTestCase extends FrameworkTestCase
	{
		private $savedDatabase = null;
		
		public function setUp()
		{
			$this->savedDatabase = serialize(\ewgraFramework\Database::me());
			\ewgraFramework\Singleton::dropInstance('ewgraFramework\Database');
		}
		
		public function tearDown()
		{
			\ewgraFramework\Singleton::setInstance(
				'ewgraFramework\Database',
				unserialize($this->savedDatabase)
			);
		}
		
		public function testIsSingleton()
		{
			$this->assertTrue(
				\ewgraFramework\Database::me() instanceof \ewgraFramework\Singleton
			);
		}
		
		public function testPoolOperations()
		{
			$this->assertFalse(\ewgraFramework\Database::me()->hasPool('default'));
			$this->assertFalse(\ewgraFramework\Database::me()->hasPool('default2'));
			
			try {
				\ewgraFramework\Database::me()->getPool('default2');
				$this->fail();
			} catch (\ewgraFramework\MissingArgumentException $e) {
				# good
			}
			
			$pool = \ewgraFramework\MysqlDatabase::create();
			
			\ewgraFramework\Database::me()->addPool($pool, 'default');
			
			\ewgraFramework\Database::me()->addPool(
				\ewgraFramework\MysqlDatabase::create(), 'default2'
			);
			
			$this->assertTrue(
				\ewgraFramework\Database::me()->hasPool('default')
			);
			
			$this->assertTrue(
				\ewgraFramework\Database::me()->hasPool('default2')
			);
			
			$this->assertSame(
				$pool, 
				\ewgraFramework\Database::me()->getPool('default')
			);
		}
	}
?>