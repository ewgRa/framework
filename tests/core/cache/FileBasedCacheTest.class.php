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
			$cacheTicket = $this->realization->createTicket()->
				setPrefix($this->prefix)->
				setKey(rand())->
				setData($this->cacheData)->
				storeData()->
				setData(null)->
				restoreData();
							
			$this->assertEqual(
				$this->cacheData,
				$cacheTicket->getData()
			);
		}

		function testGetExpired()
		{
			$cacheTicket = $this->realization->createTicket();

			$cacheTicket->
				setActualTime($cacheTicket->getLifeTime()+1)->
				setPrefix($this->prefix)->
				setKey(rand())->
				setData($this->cacheData)->
				storeData()->
				setData(null)->
				restoreData();

			$this->assertTrue($cacheTicket->isExpired());
		}
	}
?>