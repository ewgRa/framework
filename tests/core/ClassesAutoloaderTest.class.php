<?php
	/* $Id$ */

	class ClassesAutoloaderTest extends UnitTestCase
	{
		private $savedClassesAutoloader = null;
		
		public function setUp()
		{
			$this->savedClassesAutoloader = serialize(ClassesAutoloader::me());
			
			Singleton::dropInstance('ClassesAutoloader');
		}
		
		public function tearDown()
		{
			Singleton::setInstance(
				'ClassesAutoloader',
				unserialize($this->savedClassesAutoloader)
			);
		}
		
		public function testIsSingleton()
		{
			$this->assertTrue(ClassesAutoloader::me() instanceof Singleton);
		}
		
		public function testLoad()
		{
			$className = 'testLoadClass' . rand();
			
			file_put_contents(
				TMP_DIR . DIRECTORY_SEPARATOR . $className . '.class.php',
				str_replace(get_class($this), $className, file_get_contents(__FILE__))
			);
			
			$searchDirs = ClassesAutoLoader::me()->getSearchDirectories();
			ClassesAutoLoader::me()->setSearchDirectories(array(TMP_DIR));
			ClassesAutoloader::me()->load($className);
			ClassesAutoLoader::me()->setSearchDirectories($searchDirs);
			
			unlink(TMP_DIR . DIRECTORY_SEPARATOR . $className . '.class.php');
			
			if(!class_exists($className))
				$this->fail();
		}
	}
?>