<?php
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
			$className = __FUNCTION__.rand();
			
			$fileName =
				TMP_DIR.DIRECTORY_SEPARATOR
				.$className.ClassesAutoLoader::CLASS_FILE_EXTENSION;
				
			file_put_contents(
				$fileName,
				str_replace(
					get_class($this),
					$className,
					file_get_contents(__FILE__)
				)
			);
			
			ClassesAutoLoader::me()->setSearchDirectories(array(TMP_DIR));
			
			$this->assertClassMapChanged(false);
			ClassesAutoloader::me()->load($className);
			$this->assertClassMapChanged(true);
			
			unlink($fileName);
			
			$this->assertTrue(class_exists($className, false));
		}
		
		public function testLoadClasses()
		{
			$className = __FUNCTION__.rand();
			
			$fileName =
				TMP_DIR.DIRECTORY_SEPARATOR
				.$className.ClassesAutoLoader::CLASS_FILE_EXTENSION;
				
			file_put_contents(
				$fileName,
				str_replace(
					get_class($this),
					$className,
					file_get_contents(__FILE__)
				)
			);
			
			ClassesAutoLoader::me()->setSearchDirectories(array(TMP_DIR));
			
			ClassesAutoloader::me()->loadAllClasses();
			
			unlink($fileName);
			
			$this->assertTrue(class_exists($className, false));
		}
		
		public function testLoadInterface()
		{
			$interfaceName = __FUNCTION__.rand();
			
			$fileName =
				TMP_DIR.DIRECTORY_SEPARATOR
				.$interfaceName.ClassesAutoLoader::CLASS_FILE_EXTENSION;
				
			file_put_contents(
				$fileName,
				"<?php
					interface {$interfaceName}{}
				?>"
			);
			
			ClassesAutoLoader::me()->addSearchDirectories(array(TMP_DIR));

			$this->assertClassMapChanged(false);
			ClassesAutoloader::me()->load($interfaceName);
			$this->assertClassMapChanged(true);
			
			unlink($fileName);
			
			$this->assertTrue(interface_exists($interfaceName, false));
			
			$this->assertSame(
				array($interfaceName => $fileName),
				ClassesAutoLoader::me()->getFoundClasses()
			);
		}
		
		public function testSetFoundClasses()
		{
			$interfaceName = __FUNCTION__.rand();
			
			$fileName =
				TMP_DIR.DIRECTORY_SEPARATOR
				.$interfaceName.ClassesAutoLoader::CLASS_FILE_EXTENSION;
				
			file_put_contents(
				$fileName,
				"<?php
					interface {$interfaceName}{}
				?>"
			);
			
			ClassesAutoLoader::me()->setFoundClasses(
				array($interfaceName => $fileName)
			);

			$this->assertClassMapChanged(false);
			ClassesAutoloader::me()->load($interfaceName);
			$this->assertClassMapChanged(false);
			
			unlink($fileName);
			
			$this->assertTrue(interface_exists($interfaceName, false));
		}
		
		private function assertClassMapChanged($expect = true)
		{
			$this->assertTrue(
				ClassesAutoloader::me()->isClassMapChanged() === $expect
			);
		}
	}
?>