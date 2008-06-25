<?php
	class FileBasedCacheTest extends UnitTestCase
	{
		public $cacheData = array('asdasd', 'dvsd', 'qweqwe');
		
		public $prefix = 'prefix';
		
		public $realization = null;
		
		function setUp()
		{
			$cacheDataDir = TMP_DIR . DIRECTORY_SEPARATOR . 'cacheData';
			
			mkdir(TMP_DIR . DIRECTORY_SEPARATOR . 'cacheData');
			
			$this->realization =
				FileBasedCache::create()->setCacheDir($cacheDataDir);
		}
		
		function tearDown()
		{
			FrameworkAllTests::deleteDir(TMP_DIR . DIRECTORY_SEPARATOR . 'cacheData');
		}

		function testSetAndGet()
		{
			$key = array( rand() );
			
			$this->realization->set(
				$this->cacheData,
				time() + $this->realization->getDefaultLifeTime(),
				$key,
				$this->prefix
			);
			
			$this->assertEqual(
				$this->cacheData,
				$this->realization->get($key, $this->prefix)
			);
		}

		function testGetExpired()
		{
			$key = array( rand() );
			
			$this->realization->set(
				$this->cacheData,
				time() - 1,
				$key,
				$this->prefix
			);
			
			$this->realization->get($key, $this->prefix);
			
			$this->assertTrue($this->realization->isExpired());
		}
		
		function testSetAfterGet()
		{
			$key = array( rand() );
			
			$this->realization->get($key, $this->prefix);
			$this->realization->set($this->cacheData);
			
			$this->assertEqual(
				$this->cacheData,
				$this->realization->get($key, $this->prefix)
			);
		}
	}
?>