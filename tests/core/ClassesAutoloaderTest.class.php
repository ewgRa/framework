<?php
	/* $Id$ */

	class ClassesAutoloaderTest extends UnitTestCase
	{
		function testIsSingleton()
		{
			$this->assertTrue(ClassesAutoloader::me() instanceof Singleton);
		}
		
		public function testLoad()
		{
			if(ClassesAutoloader::me()->getCacheRealization())
			{
				ClassesAutoloader::me()->getCacheRealization()->disable();
			}
			
			$className = 'testLoadClass' . rand();
			file_put_contents(
				TMP_DIR . DIRECTORY_SEPARATOR . $className . '.class.php',
				str_replace(get_class($this), $className, file_get_contents(__FILE__))
			);
			
			ClassesAutoLoader::me()->addSearchDirectories(array(TMP_DIR));
			ClassesAutoloader::me()->load($className);
			
			if(!class_exists($className))
			{
				$this->fail();
			}
			
			unlink(TMP_DIR . DIRECTORY_SEPARATOR . $className . '.class.php');
			
			ClassesAutoloader::me()->dropFound($className);

			if(ClassesAutoloader::me()->getCacheRealization())
			{
				ClassesAutoloader::me()->getCacheRealization()->enable();
			}
		}
	}
?>