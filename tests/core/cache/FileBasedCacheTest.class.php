<?php
	class FileBasedCacheTest extends UnitTestCase 
	{
		public $cacheData = array('asdasd', 'dvsd', 'qweqwe');
		
		public $prefix = 'prefix';
		
		public $connector = null;
		
		function setUp()
		{
			$cacheDataDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacheData';
			
			mkdir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacheData');
			
			$this->connector =
				FileBasedCacheConnector::create()->setCacheDir($cacheDataDir);
		}
		
		function tearDown()
		{
			FrameworkAllTests::deleteDir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacheData');
		}

		function testSetAndGet()
		{
			$key = array( rand() );
			
			$this->connector->setData(
				$this->cacheData,
				time() + $this->connector->getDefaultLifeTime(),
				$key,
				$this->prefix
			);
			
			$this->assertEqual(
				$this->cacheData,
				$this->connector->getData($key, $this->prefix)
			);
		}

		function testGetExpired()
		{
			$key = array( rand() );
			
			$this->connector->setData(
				$this->cacheData,
				time() - 1,
				$key,
				$this->prefix
			);
			
			$this->connector->getData($key, $this->prefix);
			
			$this->assertTrue($this->connector->isExpired());
		}
		
		function testSetAfterGet()
		{
			$key = array( rand() );
			
			$this->connector->getData($key, $this->prefix);
			$this->connector->setData($this->cacheData);
			
			$this->assertEqual(
				$this->cacheData,
				$this->connector->getData($key, $this->prefix)
			);
		}		
	}
?>