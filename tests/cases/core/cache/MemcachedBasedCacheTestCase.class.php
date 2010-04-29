<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MemcachedBasedCacheTestCase extends BaseCacheTest
	{
		protected function getRealization()
		{
			return
				MemcachedBasedCache::create()->
				setHost(MEMCACHED_TEST_HOST)->
				setPort(MEMCACHED_TEST_PORT);
		}
	}
?>