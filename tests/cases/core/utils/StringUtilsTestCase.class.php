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
				'Testtest',
				StringUtils::upperKeyFirstAlpha('testtest')
			);
		}

		public function testSeparateByUpperKey()
		{
			$this->assertEquals(
				'test_string_test',
				StringUtils::separateByUpperKey('testStringTest')
			);
		}

		public function testGetLength()
		{
			$this->assertEquals(
				10,
				StringUtils::getLength('testString')
			);
		}
		
		public function testToLower()
		{
			$this->assertEquals(
				'teststringtest',
				StringUtils::toLower('testStringTest')
			);
		}

		public function testToUpper()
		{
			$this->assertEquals(
				'TESTSTRINGTEST',
				StringUtils::toUpper('testStringTest')
			);
		}
	}
?>