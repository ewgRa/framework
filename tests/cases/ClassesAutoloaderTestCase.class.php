<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ClassesAutoloaderTestCase extends FrameworkTestCase
	{
		private $savedClassesAutoloader = null;

		public function setUp()
		{
			$this->savedClassesAutoloader = serialize(\ewgraFramework\ClassesAutoloader::me());
			\ewgraFramework\TestSingleton::dropInstance('ewgraFramework\ClassesAutoloader');
		}

		public function tearDown()
		{
			\ewgraFramework\TestSingleton::setInstance(
				'ewgraFramework\ClassesAutoloader',
				unserialize($this->savedClassesAutoloader)
			);
		}

		public function testIsSingleton()
		{
			$this->assertTrue(
				\ewgraFramework\ClassesAutoloader::me() instanceof \ewgraFramework\Singleton
			);
		}

		public function testLoadClass()
		{
			$className = __FUNCTION__.rand();

			$fullClassName = __NAMESPACE__.'\\'.$className;

			$fileName =
				TMP_DIR.DIRECTORY_SEPARATOR
				.$className.\ewgraFramework\ClassesAutoLoader::CLASS_FILE_EXTENSION;

			file_put_contents(
				$fileName,
				str_replace(
					\ewgraFramework\StringUtils::getClassName(__CLASS__),
					$className,
					file_get_contents(__FILE__)
				)
			);

			\ewgraFramework\ClassesAutoLoader::me()->addSearchDirectory(TMP_DIR);

			$this->assertClassMapChanged(false);
			\ewgraFramework\ClassesAutoloader::me()->load($fullClassName);
			$this->assertClassMapChanged(true);

			unlink($fileName);

			$this->assertTrue(class_exists($fullClassName, false));
		}

		public function testLoadClasses()
		{
			$className = __FUNCTION__.rand();

			$fullClassName = __NAMESPACE__.'\\'.$className;

			$fileName =
				TMP_DIR.DIRECTORY_SEPARATOR
				.$className.\ewgraFramework\ClassesAutoLoader::CLASS_FILE_EXTENSION;

			file_put_contents(
				$fileName,
				str_replace(
					\ewgraFramework\StringUtils::getClassName(__CLASS__),
					$className,
					file_get_contents(__FILE__)
				)
			);

			\ewgraFramework\ClassesAutoLoader::me()->addSearchDirectory(TMP_DIR);

			\ewgraFramework\ClassesAutoloader::me()->loadAllClasses();

			unlink($fileName);

			$this->assertTrue(class_exists($fullClassName, false));
		}

		public function testLoadInterface()
		{
			$interfaceName = __FUNCTION__.rand();

			$fullInterfaceName = __NAMESPACE__.'\\'.$interfaceName;

			$fileName =
				TMP_DIR.DIRECTORY_SEPARATOR
				.$interfaceName.\ewgraFramework\ClassesAutoLoader::CLASS_FILE_EXTENSION;

			file_put_contents(
				$fileName,
				"<?php
					namespace ".__NAMESPACE__.";
					interface {$interfaceName}{}
				?>"
			);

			\ewgraFramework\ClassesAutoLoader::me()->addSearchDirectory(TMP_DIR);

			$this->assertClassMapChanged(false);

			\ewgraFramework\ClassesAutoloader::me()->load($fullInterfaceName);

			$this->assertClassMapChanged(true);

			unlink($fileName);

			$this->assertTrue(interface_exists($fullInterfaceName, false));

			$this->assertSame(
				array($fullInterfaceName => $fileName),
				\ewgraFramework\ClassesAutoLoader::me()->getFoundClasses()
			);
		}

		public function testSetFoundClasses()
		{
			$interfaceName = __FUNCTION__.rand();

			$fileName =
				TMP_DIR.DIRECTORY_SEPARATOR
				.$interfaceName.\ewgraFramework\ClassesAutoLoader::CLASS_FILE_EXTENSION;

			file_put_contents(
				$fileName,
				"<?php
					interface {$interfaceName}{}
				?>"
			);

			\ewgraFramework\ClassesAutoLoader::me()->setFoundClasses(
				array($interfaceName => $fileName)
			);

			$this->assertClassMapChanged(false);
			\ewgraFramework\ClassesAutoloader::me()->load($interfaceName);
			$this->assertClassMapChanged(false);

			unlink($fileName);

			$this->assertTrue(interface_exists($interfaceName, false));
		}

		public function testNamespace()
		{
			$className = __FUNCTION__.rand();

			$fullClassName = __NAMESPACE__.'Additional\\'.$className;

			$fileName =
				TMP_DIR.DIRECTORY_SEPARATOR
				.$className.\ewgraFramework\ClassesAutoLoader::CLASS_FILE_EXTENSION;

			file_put_contents(
				$fileName,
				"<?php
					namespace ".__NAMESPACE__."Additional;
					class {$className}{}
				?>"
			);

			\ewgraFramework\ClassesAutoLoader::me()->addSearchDirectory(TMP_DIR, __NAMESPACE__);

			$this->assertClassMapChanged(false);

			\ewgraFramework\ClassesAutoloader::me()->load($fullClassName);

			$this->assertClassMapChanged(false);

			$this->assertFalse(class_exists($fullClassName, false));

			\ewgraFramework\ClassesAutoLoader::me()->addSearchDirectory(TMP_DIR, __NAMESPACE__.'Additional');

			$this->assertClassMapChanged(false);

			\ewgraFramework\ClassesAutoloader::me()->load($fullClassName);

			$this->assertClassMapChanged(true);

			unlink($fileName);

			$this->assertTrue(class_exists($fullClassName, false));

			$this->assertSame(
				array($fullClassName => $fileName),
				\ewgraFramework\ClassesAutoLoader::me()->getFoundClasses()
			);
		}

		public function testDropClass()
		{
			$className = __FUNCTION__.rand();

			$fullClassName = __NAMESPACE__.'\\'.$className;

			$fileName =
				TMP_DIR.DIRECTORY_SEPARATOR
				.$className.\ewgraFramework\ClassesAutoLoader::CLASS_FILE_EXTENSION;

			\ewgraFramework\ClassesAutoLoader::me()->setFoundClasses(
				array(
					$fullClassName => $fileName
				)
			);

			\ewgraFramework\ClassesAutoLoader::me()->addSearchDirectory(TMP_DIR);

			$this->assertClassMapChanged(false);
			$this->assertNotNull(\ewgraFramework\ClassesAutoloader::me()->getFoundClassFile($fullClassName));

			\ewgraFramework\ClassesAutoloader::me()->load($fullClassName);

			$this->assertClassMapChanged(true);
			$this->assertFalse(class_exists($fullClassName, false));
			$this->assertNull(\ewgraFramework\ClassesAutoloader::me()->getFoundClassFile($fullClassName));
		}

		private function assertClassMapChanged($expect = true)
		{
			$this->assertTrue(
				\ewgraFramework\ClassesAutoloader::me()->isClassMapChanged() === $expect
			);
		}
	}
?>