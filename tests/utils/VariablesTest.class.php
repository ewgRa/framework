<?php
	/* $Id$ */

	define( 'TEST_CONST', 'value' );
	
	class VariablesTest extends UnitTestCase
	{
		public function testGetValueByString()
		{
			$this->assertEqual(
				Variables::getValueByString('TEST_CONST'), TEST_CONST
			);

			$this->assertEqual(
				Variables::getValueByString('UNDEFINED_CONST'), null
			);
			
			$this->assertEqual(
				Variables::getValueByString('$_SERVER'), $_SERVER
			);

			$this->assertEqual(
				Variables::getValueByString('$_SERVER[REMOTE_ADDR]'),
				$_SERVER['REMOTE_ADDR']
			);

			$this->assertEqual(
				Variables::getValueByString('$_SERVER[\'REMOTE_ADDR\']'),
				$_SERVER['REMOTE_ADDR']
			);
			
			$this->assertEqual(
				Variables::getValueByString('$_SERVER["REMOTE_ADDR"]'),
				$_SERVER['REMOTE_ADDR']
			);
			
			$this->assertEqual(
				Variables::getValueByString('_SERVER["REMOTE_ADDR"]'),
				null
			);
			
			$this->assertEqual(
				Variables::getValueByString('$_SERVER[unlink(aaaaa)]'),
				null
			);
			
			$this->assertEqual(
				Variables::getValueByString('$undefined'),
				null
			);
		}

		public function testRegisterAsConstants()
		{
			$constantValue = rand();
			
			Variables::registerAsConstants(
				array('testConstant' => $constantValue)
			);
			
			$this->assertTrue(defined('testConstant'));
			$this->assertEqual(constant('testConstant'), $constantValue);
		}
	}
?>