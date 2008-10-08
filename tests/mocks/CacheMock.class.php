<?php
	/* $Id$ */

	class CacheMock
	{
		private $savedCache = null;
		
		public static function create()
		{
			$this->savedCache = serialize(Cache::me());
			
			Mock::generate('Cache', 'CacheTestMock');
			$cache = &new CacheTestMock();
			
			Singleton::setInstance('Cache', $cache);
			
			return $cache;
		}

		public static function drop()
		{
			Singleton::setInstance('Cache', unserialize($this->savedCache));
		}
	}
?>