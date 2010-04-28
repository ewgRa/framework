<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class StringUtilsTestCase extends FrameworkTestCase
	{
		public function testUpperKeyFirstAlpha()
		{
			$this->assertEquals(
				StringUtils::upperKeyFirstAlpha('testtest'),
				'Testtest'
			);
		}

		public function testSeparateByUpperKey()
		{
			$this->assertEquals(
				StringUtils::separateByUpperKey('testStringTest'),
				'test_string_test'
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
				StringUtils::toLower('testStringTest'),
				'teststringtest'
			);
		}

		public function testToUpper()
		{
			$this->assertEquals(
				StringUtils::toUpper('testStringTest'),
				'TESTSTRINGTEST'
			);
		}
	}
?>