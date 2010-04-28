<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class VariableUtilsTestCase extends FrameworkTestCase
	{
		public function testGetValueByString()
		{
			define('TEST_CONST', 'value');
	
			$this->assertEquals(
				VariableUtils::getValueByString('TEST_CONST'),
				TEST_CONST
			);

			$this->assertEquals(
				VariableUtils::getValueByString('UNDEFINED_CONST'),
				null
			);
			
			$this->assertEquals(
				VariableUtils::getValueByString('$_SERVER'),
				$_SERVER
			);

			if(!isset($_SERVER['REMOTE_ADDR']))
				$_SERVER['REMOTE_ADDR'] = rand();
			
			$this->assertEquals(
				VariableUtils::getValueByString('$_SERVER[REMOTE_ADDR]'),
				$_SERVER['REMOTE_ADDR']
			);

			$this->assertEquals(
				VariableUtils::getValueByString('$_SERVER[\'REMOTE_ADDR\']'),
				$_SERVER['REMOTE_ADDR']
			);
			
			$this->assertEquals(
				VariableUtils::getValueByString('$_SERVER["REMOTE_ADDR"]'),
				$_SERVER['REMOTE_ADDR']
			);
			
			$this->assertEquals(
				VariableUtils::getValueByString('_SERVER["REMOTE_ADDR"]'),
				null
			);
			
			$this->assertEquals(
				VariableUtils::getValueByString('$_SERVER[unlink(aaaaa)]'),
				null
			);
			
			$this->assertEquals(
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
			$this->assertEquals(constant('testConstant'), $constantValue);
		}
	}
?>