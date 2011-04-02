<?php
	namespace ewgraFramework\tests;

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
				\ewgraFramework\StringUtils::upperKeyFirstAlpha('testtest')
			);
		}

		public function testSeparateByUpperKey()
		{
			$this->assertEquals(
				'test_string_test',
				\ewgraFramework\StringUtils::separateByUpperKey('testStringTest')
			);
		}

		public function testGetLength()
		{
			$this->assertEquals(
				10,
				\ewgraFramework\StringUtils::getLength('testString')
			);
		}

		public function testToLower()
		{
			$this->assertEquals(
				'teststringtest',
				\ewgraFramework\StringUtils::toLower('testStringTest')
			);
		}

		public function testToUpper()
		{
			$this->assertEquals(
				'TESTSTRINGTEST',
				\ewgraFramework\StringUtils::toUpper('testStringTest')
			);
		}
	}
?>