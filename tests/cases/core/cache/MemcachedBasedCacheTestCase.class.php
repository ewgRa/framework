<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MemcachedBasedCacheTestCase extends BaseCacheTest
	{
		protected function getRealization()
		{
			return Cache::me()->getPool(MEMCACHED_TEST_POOL_ALIAS);
		}
	}
?>