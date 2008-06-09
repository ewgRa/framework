<?php
	class CacheMock
	{
		public static function create()
		{
			Mock::generate('Cache', 'CacheTestMock');
			$cache = &new CacheTestMock();
			MyCache::setInstance($cache);
			return $cache;
		}

		public static function drop()
		{
			MyCache::dropInstance();
		}
	}
?>