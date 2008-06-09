<?php
	class CacheMock
	{
		public static function create()
		{
			Mock::generate('Cache', 'CacheTestMock');
			$cache = &new CacheTestMock();
			MyTestCache::setInstance($cache);
			return $cache;
		}

		public static function drop()
		{
			MyTestCache::dropInstance();
		}
	}
?>