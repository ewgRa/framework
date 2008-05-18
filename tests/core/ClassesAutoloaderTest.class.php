<?php
	class ClassesAutoloaderTest extends UnitTestCase 
	{
		function testIsSingleton()
		{
			$this->assertTrue(ClassesAutoloader::me() instanceof Singleton);
		}
		
		public function testLoad()
		{
			if(ClassesAutoloader::me()->getCacheConnector())
			{
				ClassesAutoloader::me()->getCacheConnector()->disable();
			}
			
			$className = 'testLoadClass' . rand();
			file_put_contents(
				dirname(__FILE__) . DIRECTORY_SEPARATOR . $className . '.class.php',
				str_replace(get_class($this), $className, file_get_contents(__FILE__))
			);
			
			ClassesAutoloader::me()->load($className);
			
			if(!class_exists($className))
			{
				$this->fail();
			}
			
			unlink(dirname(__FILE__) . DIRECTORY_SEPARATOR . $className . '.class.php');
			
			ClassesAutoloader::me()->dropFound($className);

			if(ClassesAutoloader::me()->getCacheConnector())
			{
				ClassesAutoloader::me()->getCacheConnector()->enable();
			}
		}
	}
?>