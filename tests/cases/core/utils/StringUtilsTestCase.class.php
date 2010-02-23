<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class StringUtilsTestCase extends FrameworkTestCase
	{
		public function testUpperKeyFirstAlpha()
		{
			$this->assertEquals(
				StringUtils::upperKeyFirstAlpha('test'),
				'Test'
			);
		}

		public function testSeparateByUpperKey()
		{
			$this->assertEquals(
				StringUtils::separateByUpperKey('testString'),
				'test_string'
			);
		}

		public function testGetLength()
		{
			$this->assertEquals(
				StringUtils::getLength('testString'),
				10
			);
		}
		
		public function testToLower()
		{
			$this->assertEquals(
				StringUtils::toLower('testString'),
				'teststring'
			);
		}

		public function testToUpper()
		{
			$this->assertEquals(
				StringUtils::toUpper('testString'),
				'TESTSTRING'
			);
		}
	}
?>