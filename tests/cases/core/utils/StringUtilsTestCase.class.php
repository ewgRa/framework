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

			$this->assertEquals(
				'Тесттест',
				\ewgraFramework\StringUtils::upperKeyFirstAlpha('тесттест')
			);
		}

		public function testLowerKeyFirstAlpha()
		{
			$this->assertEquals(
				'tEsttest',
				\ewgraFramework\StringUtils::lowerKeyFirstAlpha('TEsttest')
			);

			$this->assertEquals(
				'тЕсттест',
				\ewgraFramework\StringUtils::lowerKeyFirstAlpha('ТЕсттест')
			);
		}

		public function testGetLength()
		{
			$this->assertEquals(
				10,
				\ewgraFramework\StringUtils::getLength('testString')
			);

			$this->assertEquals(
				10,
				\ewgraFramework\StringUtils::getLength('тестСтрока')
			);
		}

		public function testGetClassNamespace()
		{
			$this->assertEquals(
				__NAMESPACE__,
				\ewgraFramework\StringUtils::getClassNamespace(get_class($this))
			);
		}

		public function testToLower()
		{
			$this->assertEquals(
				'teststringtest',
				\ewgraFramework\StringUtils::toLower('testStringTest')
			);

			$this->assertEquals(
				'тестстрокатест',
				\ewgraFramework\StringUtils::toLower('тестСтрокаТест')
			);
		}

		public function testToUpper()
		{
			$this->assertEquals(
				'TESTSTRINGTEST',
				\ewgraFramework\StringUtils::toUpper('testStringTest')
			);

			$this->assertEquals(
				'ТЕСТСТРОКАТЕСТ',
				\ewgraFramework\StringUtils::toUpper('тестСтрокаТест')
			);
		}
	}
?>