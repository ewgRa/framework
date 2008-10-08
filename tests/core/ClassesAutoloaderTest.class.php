<?php
	/* $Id$ */

	class ClassesAutoloaderTest extends UnitTestCase
	{
		public function testIsSingleton()
		{
			$this->assertTrue(ClassesAutoloader::me() instanceof Singleton);
		}
		
		public function testLoad()
		{
			$cacheDisabled = true;
			
			if(ClassesAutoloader::me()->hasCacheTicket())
			{
				$cacheDisabled = $this->getCacheRealization()->isDisabled();
				
				if(!$cacheDisabled)
					$this->getCacheRealization()->disable();
			}
			
			$className = 'testLoadClass' . rand();
			
			file_put_contents(
				TMP_DIR . DIRECTORY_SEPARATOR . $className . '.class.php',
				str_replace(get_class($this), $className, file_get_contents(__FILE__))
			);
			
			$searchDirs = ClassesAutoLoader::me()->getSearchDirectories();
			ClassesAutoLoader::me()->setSearchDirectories(array(TMP_DIR));
			ClassesAutoloader::me()->load($className);
			ClassesAutoLoader::me()->setSearchDirectories($searchDirs);
			
			if(!class_exists($className))
				$this->fail();
			
			unlink(TMP_DIR . DIRECTORY_SEPARATOR . $className . '.class.php');

			if(!$cacheDisabled)
				$this->getCacheRealization()->enable();
		}
		
		private function getCacheRealization()
		{
			return ClassesAutoloader::me()->
				getCacheTicket()->
				getCacheRealization();
		}
	}
?>