<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ClassesAutoloaderTestCase extends FrameworkTestCase
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
			
			$fileName =
				TMP_DIR . DIRECTORY_SEPARATOR
				. $className . ClassesAutoLoader::CLASS_FILE_EXTENSION;
				
			file_put_contents(
				$fileName,
				str_replace(get_class($this), $className, file_get_contents(__FILE__))
			);
			
			ClassesAutoLoader::me()->setSearchDirectories(array(TMP_DIR));
			ClassesAutoloader::me()->load($className);
			
			unlink($fileName);
			
			$this->assertTrue(class_exists($className, false));
		}
		
		public function testLoadInterface()
		{
			$interfaceName = __FUNCTION__ . rand();
			
			$fileName =
				TMP_DIR . DIRECTORY_SEPARATOR
				. $interfaceName . ClassesAutoLoader::CLASS_FILE_EXTENSION;
				
			file_put_contents(
				$fileName,
				"<?php
					interface {$interfaceName}{}
				?>"
			);
			
			ClassesAutoLoader::me()->setSearchDirectories(array(TMP_DIR));
			ClassesAutoloader::me()->load($interfaceName);
			
			unlink($fileName);
			
			$this->assertTrue(interface_exists($interfaceName, false));
		}
	}
?>