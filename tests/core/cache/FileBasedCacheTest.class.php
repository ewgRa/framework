<?php
	class FileBasedCacheTest extends UnitTestCase 
	{
		public $cacheData = array('asdasd', 'dvsd', 'qweqwe');
		
		public $prefix = 'prefix';
		
		public $realization = null;
		
		function setUp()
		{
			$cacheDataDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacheData';
			
			mkdir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacheData');
			
			$this->realization =
				FileBasedCache::create()->setCacheDir($cacheDataDir);
		}
		
		function tearDown()
		{
			FrameworkAllTests::deleteDir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacheData');
		}

		function testSetAndGet()
		{
			$key = array( rand() );
			
			$this->realization->setData(
				$this->cacheData,
				time() + $this->realization->getDefaultLifeTime(),
				$key,
				$this->prefix
			);
			
			$this->assertEqual(
				$this->cacheData,
				$this->realization->getData($key, $this->prefix)
			);
		}

		function testGetExpired()
		{
			$key = array( rand() );
			
			$this->realization->setData(
				$this->cacheData,
				time() - 1,
				$key,
				$this->prefix
			);
			
			$this->realization->getData($key, $this->prefix);
			
			$this->assertTrue($this->realization->isExpired());
		}
		
		function testSetAfterGet()
		{
			$key = array( rand() );
			
			$this->realization->getData($key, $this->prefix);
			$this->realization->setData($this->cacheData);
			
			$this->assertEqual(
				$this->cacheData,
				$this->realization->getData($key, $this->prefix)
			);
		}		
	}
?>