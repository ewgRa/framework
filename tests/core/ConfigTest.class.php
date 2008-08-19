<?php
	/* $Id$ */

	class ConfigTest extends UnitTestCase
	{
		private $yamlFile = null;

		public function __construct()
		{
			$this->yamlFile = dirname(__FILE__) . DIRECTORY_SEPARATOR
				. 'config.test.yml';
				
		}
		
		function setUp()
		{
			Singleton::dropInstance('Config');
		}
		
		function tearDown()
		{
			Singleton::dropInstance('Config');
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
				initialize($this->yamlFile);
			
			$this->assertEqual(
				MyTestConfig::me()->getOption('testArray'),
				array(
					'arrayKey1' => 'arrayValue1',
					'arrayKey2' => 'arrayValue2'
				)
			);
		}
	}
?>