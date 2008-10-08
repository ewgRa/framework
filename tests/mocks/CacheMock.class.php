<?php
	/* $Id$ */

	class CacheMock
	{
		public static function create()
		{
			Mock::generate('Cache', 'CacheTestMock');
			$cache = &new CacheTestMock();
			
			Singleton::setInstance('Cache', $cache);
			
			return $cache;
		}

		public static function drop()
		{
			Singleton::dropInstance('Cache');
		}
	}
?>