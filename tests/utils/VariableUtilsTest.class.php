<?php
	/* $Id$ */

	define( 'TEST_CONST', 'value' );
	
	class VariableUtilsTest extends UnitTestCase
	{
		public function testGetValueByString()
		{
			$this->assertEqual(
				VariableUtils::getValueByString('TEST_CONST'), TEST_CONST
			);

			$this->assertEqual(
				VariableUtils::getValueByString('UNDEFINED_CONST'), null
			);
			
			$this->assertEqual(
				VariableUtils::getValueByString('$_SERVER'), $_SERVER
			);

			if(!isset($_SERVER['REMOTE_ADDR']))
				$_SERVER['REMOTE_ADDR'] = rand();
			
			$this->assertEqual(
				VariableUtils::getValueByString('$_SERVER[REMOTE_ADDR]'),
				$_SERVER['REMOTE_ADDR']
			);

			$this->assertEqual(
				VariableUtils::getValueByString('$_SERVER[\'REMOTE_ADDR\']'),
				$_SERVER['REMOTE_ADDR']
			);
			
			$this->assertEqual(
				VariableUtils::getValueByString('$_SERVER["REMOTE_ADDR"]'),
				$_SERVER['REMOTE_ADDR']
			);
			
			$this->assertEqual(
				VariableUtils::getValueByString('_SERVER["REMOTE_ADDR"]'),
				null
			);
			
			$this->assertEqual(
				VariableUtils::getValueByString('$_SERVER[unlink(aaaaa)]'),
				null
			);
			
			$this->assertEqual(
				VariableUtils::getValueByString('$undefined'),
				null
			);
		}

		public function testRegisterAsConstants()
		{
			$constantValue = rand();
			
			VariableUtils::registerAsConstants(
				array('testConstant' => $constantValue)
			);
			
			$this->assertTrue(defined('testConstant'));
			$this->assertEqual(constant('testConstant'), $constantValue);
		}
	}
?>