<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class CacheTestCase extends FrameworkTestCase
	{
		private $savedCache = null;
		
		public function setUp()
		{
			$this->savedCache = serialize(Cache::me());
			Singleton::dropInstance('Cache');
		}
		
		public function tearDown()
		{
			Singleton::setInstance(
				'Cache',
				unserialize($this->savedCache)
			);
		}
		
		public function testIsSingleton()
		{
			$this->assertTrue(Cache::me() instanceof Singleton);
		}
		
		public function testPoolOperations()
		{
			$this->assertFalse(Cache::me()->hasPool('default'));
			$this->assertFalse(Cache::me()->hasPool('default2'));
			
			try {
				Cache::me()->getPool('default2');
				$this->fail();
			} catch (MissingArgumentException $e) {
				# good
			}
			
			$pool = FileBasedCache::create();
			
			Cache::me()->addPool($pool, 'default');
			Cache::me()->addPool(FileBasedCache::create(), 'default2');
			
			$this->assertTrue(Cache::me()->hasPool('default'));
			$this->assertTrue(Cache::me()->hasPool('default2'));
			
			$this->assertSame($pool, Cache::me()->getPool('default'));
		}
	}
?>