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
		
		public function testLoadClass()
		{
			$className = __FUNCTION__ . rand();
			
			file_put_contents(
				TMP_DIR . DIRECTORY_SEPARATOR . $className . ClassesAutoLoader::CLASS_FILE_EXTENSION,
				str_replace(get_class($this), $className, file_get_contents(__FILE__))
			);
			
			ClassesAutoLoader::me()->setSearchDirectories(array(TMP_DIR));
			ClassesAutoloader::me()->load($className);
			
			unlink(TMP_DIR . DIRECTORY_SEPARATOR . $className . ClassesAutoLoader::CLASS_FILE_EXTENSION);
			
			if(!class_exists($className))
				$this->fail();
		}
		
		public function testLoadInterface()
		{
			$interfaceName = __FUNCTION__ . rand();
			
			file_put_contents(
				TMP_DIR . DIRECTORY_SEPARATOR . $interfaceName . ClassesAutoLoader::CLASS_FILE_EXTENSION,
				"<?php
					interface {$interfaceName}{}
				?>"
			);
			
			ClassesAutoLoader::me()->setSearchDirectories(array(TMP_DIR));
			ClassesAutoloader::me()->load($interfaceName);
			
			unlink(TMP_DIR . DIRECTORY_SEPARATOR . $interfaceName . ClassesAutoLoader::CLASS_FILE_EXTENSION);
			
			if(!interface_exists($interfaceName))
				$this->fail();
		}
	}
?>