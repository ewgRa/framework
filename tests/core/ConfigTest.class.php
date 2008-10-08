<?php
	/* $Id$ */

	class ConfigTest extends UnitTestCase
	{
		private $savedConfig = null;
		private $yamlFile	 = null;

		public function __construct()
		{
			$this->yamlFile = dirname(__FILE__) . DIRECTORY_SEPARATOR
				. 'config.test.yml';
			
		}
		
		function setUp()
		{
			$this->savedConfig = serialize(Config::me());
			Singleton::dropInstance('Config');
		}
		
		function tearDown()
		{
			Singleton::setInstance('Config', unserialize($this->savedConfig));
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
				Config::me()->replaceVariables($variable),
				$rightVariable
			);
			
			$this->assertEqual(
				Config::me()->replaceVariables($variable[0]),
				$rightVariable[0]
			);
		}
		
		function testSetAndGetOption()
		{
			$variable = array(rand());
			Config::me()->setOption('testval', $variable);
			
			$this->assertEqual(
				Config::me()->getOption('testval'),
				$variable
			);

			Config::me()->setOption('testval', $variable[0]);
			
			$this->assertEqual(
				Config::me()->getOption('testval'),
				$variable[0]
			);
		}
		
		function testNoYamlFile()
		{
			try
			{
				Config::me()->
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
			Config::me()->
				initialize($this->yamlFile);
			
			$this->assertEqual(
				Config::me()->getOption('testArray'),
				array(
					'arrayKey1' => 'arrayValue1',
					'arrayKey2' => 'arrayValue2'
				)
			);
		}
	}
?>