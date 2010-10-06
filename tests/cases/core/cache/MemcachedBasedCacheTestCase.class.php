<?php
	namespace ewgraFramework\tests;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MemcachedBasedCacheTestCase extends BaseCacheTest
	{
		protected function getRealization()
		{
			return
				\ewgraFramework\MemcachedBasedCache::create()->
				addServer(MEMCACHED_TEST_HOST, MEMCACHED_TEST_PORT);
		}
	}
?>