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
			\ewgraFramework\TestSingleton::dropInstance('ewgraFramework\Database');
		}

		public function tearDown()
		{
			\ewgraFramework\TestSingleton::setInstance(
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

			$pool = \ewgraFramework\MysqlDatabase::create()->setUser('baobab');

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

			\ewgraFramework\Database::me()->swapPools('default', 'default2');

			$this->assertNull(
				\ewgraFramework\Database::me()->getPool('default')->getUser()
			);

			$this->assertSame(
				$pool->getUser(),
				\ewgraFramework\Database::me()->getPool('default2')->getUser()
			);

			\ewgraFramework\Database::me()->swapPools('default', 'default2');

			$this->assertSame(
				array(
					'default' => $pool,
					'default2' => \ewgraFramework\Database::me()->getPool('default2')
				),
				\ewgraFramework\Database::me()->getPools()
			);
		}
	}
?>