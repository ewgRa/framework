<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class CacheTestCase extends FrameworkTestCase
	{
		private $savedCache = null;

		public function setUp()
		{
			$this->savedCache = serialize(\ewgraFramework\Cache::me());
			\ewgraFramework\TestSingleton::dropInstance('ewgraFramework\Cache');
		}

		public function tearDown()
		{
			\ewgraFramework\TestSingleton::setInstance(
				'ewgraFramework\Cache',
				unserialize($this->savedCache)
			);
		}

		public function testIsSingleton()
		{
			$this->assertTrue(
				\ewgraFramework\Cache::me() instanceof \ewgraFramework\Singleton
			);
		}

		public function testPoolOperations()
		{
			$this->assertFalse(\ewgraFramework\Cache::me()->hasPool('default'));
			$this->assertFalse(\ewgraFramework\Cache::me()->hasPool('default2'));

			try {
				\ewgraFramework\Cache::me()->getPool('default2');
				$this->fail();
			} catch (\ewgraFramework\MissingArgumentException $e) {
				# good
			}

			$pool = \ewgraFramework\FileBasedCache::create()->setCacheDir('test');

			\ewgraFramework\Cache::me()->addPool($pool, 'default');

			\ewgraFramework\Cache::me()->addPool(
				\ewgraFramework\FileBasedCache::create(), 'default2'
			);

			$this->assertTrue(
				\ewgraFramework\Cache::me()->hasPool('default')
			);

			$this->assertTrue(
				\ewgraFramework\Cache::me()->hasPool('default2')
			);

			$this->assertSame(
				$pool,
				\ewgraFramework\Cache::me()->getPool('default')
			);

			\ewgraFramework\Cache::me()->swapPools('default', 'default2');

			$this->assertNull(
				\ewgraFramework\Cache::me()->getPool('default')->getCacheDir()
			);

			$this->assertSame(
				$pool->getCacheDir(),
				\ewgraFramework\Cache::me()->getPool('default2')->getCacheDir()
			);

			\ewgraFramework\Cache::me()->swapPools('default', 'default2');

			$this->assertSame(
				array(
					'default' => $pool,
					'default2' => \ewgraFramework\Cache::me()->getPool('default2')
				),
				\ewgraFramework\Cache::me()->getPools()
			);
		}
	}
?>