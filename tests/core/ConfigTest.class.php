<?php
	class ConfigTest extends UnitTestCase
	{
		private $cacheDataDir = null;
		private $firstYamlFile = null;
		private $secondYamlFile = null;
		private $thirdYamlFile = null;
		
		public function __construct()
		{
			$this->cacheDataDir = TMP_DIR . DIRECTORY_SEPARATOR
				. 'cacheData';
				
			$this->firstYamlFile = dirname(__FILE__) . DIRECTORY_SEPARATOR
				. 'config.test.yml';
				
			$this->secondYamlFile = dirname(__FILE__) . DIRECTORY_SEPARATOR
				. 'config.test2.yml';

			$this->thirdYamlFile = TMP_DIR . DIRECTORY_SEPARATOR
				. 'config.test3.yml';
		}
		
		function setUp()
		{
			MyTestConfig::me()->setInstance(null);
		}
		
		function tearDown()
		{
			MyTestConfig::me()->setInstance(null);
		}
		
		function testIsSingleton()
		{
			$this->assertTrue(Config::me() instanceof Singleton);
		}

		function testReplaceVariables()
		{
			$variable = array(
				rand() . '%$_SERVER[HTTP_HOST]%' . rand()
			);
			
			$rightVariable = array(
				str_replace(
					'%$_SERVER[HTTP_HOST]%',
					$_SERVER['HTTP_HOST'],
					$variable[0]
				)
			);
			
			$this->assertEqual(
				MyTestConfig::me()->replaceVariables($variable),
				$rightVariable
			);
			
			$this->assertEqual(
				MyTestConfig::me()->replaceVariables($variable[0]),
				$rightVariable[0]
			);
		}
		
		function testSetAndGetOption()
		{
			$variable = array(rand());
			MyTestConfig::me()->setOption('testval', $variable);
			
			$this->assertEqual(
				MyTestConfig::me()->getOption('testval'),
				$variable
			);

			MyTestConfig::me()->setOption('testval', $variable[0]);
			
			$this->assertEqual(
				MyTestConfig::me()->getOption('testval'),
				$variable[0]
			);
		}
		
		function testNoYamlFile()
		{
			try
			{
				MyTestConfig::me()->
					initialize(rand() . '.yml');

				$this->fail('no exception on non exists file');
			}
			catch(FileException $e)
			{
				$this->pass($e);
			}
		}
		
		function testInitialize()
		{
			MyTestConfig::me()->
				setMergeYAMLSections(array('all', 'testSection'))->
				initialize($this->firstYamlFile);
				
			$this->assertEqual(
				MyTestConfig::me()->getOption('testArray'),
				array(
					'arrayKey1' => 'arrayValue1',
					'arrayKey2' => 'arrayValue2_testSection',
				)
			);
		}
		
		function testInitializeCache()
		{
			mkdir(TMP_DIR . DIRECTORY_SEPARATOR . 'cacheData');

			copy(
				$this->firstYamlFile,
				$this->thirdYamlFile
			);
			
			$time = time();
			touch($this->thirdYamlFile,	$time);
			
			MyTestConfig::me()->setCacheRealization(
				FileBasedCache::create()->setCacheDir($this->cacheDataDir)
			);
			
			MyTestConfig::me()->
				setMergeYAMLSections(array('all', 'testSection'))->
				initialize($this->thirdYamlFile);
			
			file_put_contents(
				$this->thirdYamlFile,
				''
			);
			
			touch($this->thirdYamlFile, $time);
			
			MyTestConfig::me()->setInstance(null);
			
			MyTestConfig::me()->
				setCacheRealization(
					FileBasedCache::create()->setCacheDir($this->cacheDataDir)
				)->
				setMergeYAMLSections(array('all', 'testSection'))->
				initialize($this->thirdYamlFile);
			
			$this->assertEqual(
				MyTestConfig::me()->getOption('testArray'),
				array(
					'arrayKey1' => 'arrayValue1',
					'arrayKey2' => 'arrayValue2_testSection',
				)
			);
			
			FrameworkAllTests::deleteDir($this->cacheDataDir);
			unlink($this->thirdYamlFile);
		}
		
		function testInitializeExpiredCache()
		{
			mkdir($this->cacheDataDir);

			copy($this->firstYamlFile, $this->thirdYamlFile);
			
			touch($this->thirdYamlFile, time());
			
			MyTestConfig::me()->setCacheRealization(
				FileBasedCache::create()->setCacheDir($this->cacheDataDir)
			);
			
			MyTestConfig::me()->
				setMergeYAMLSections(array('all', 'testSection'))->
				initialize($this->thirdYamlFile);
			
			$fileMTime = filemtime($this->thirdYamlFile);

			unlink($this->thirdYamlFile);
			copy($this->secondYamlFile, $this->thirdYamlFile);
			touch($this->thirdYamlFile, $fileMTime + 10);
			
			MyTestConfig::me()->setInstance(null);
			
			MyTestConfig::me()->
				setCacheRealization(
					FileBasedCache::create()->setCacheDir($this->cacheDataDir)
				)->
				setMergeYAMLSections(array('all', 'testSection'))->
				initialize($this->thirdYamlFile);
			
			$this->assertEqual(
				MyTestConfig::me()->getOption('testArray'),
				array(
					'arrayKey1' => 'arrayValue1',
					'arrayKey2' => 'arrayValue2_testSection_new',
				)
			);
			
			FrameworkAllTests::deleteDir($this->cacheDataDir);
			unlink($this->thirdYamlFile);
		}
	}
?>