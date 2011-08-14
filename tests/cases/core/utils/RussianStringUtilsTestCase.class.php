<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class RussianStringUtilsTestCase extends FrameworkTestCase
	{
		public function testTranslit()
		{
			$this->assertEquals(
				'Baobab',
				\ewgraFramework\RussianStringUtils::translit('Баобаб')
			);
		}

		public function testUrlTranslit()
		{
			$this->assertEquals(
				'baobab_-df',
				\ewgraFramework\RussianStringUtils::urlTranslit('Baobab.__ -дф')
			);
		}

		public function testSelectCaseForNumber()
		{
			$this->assertEquals(
				'слов',
				\ewgraFramework\RussianStringUtils::selectCaseForNumber(
					5,
					array('слово', 'слова', 'слов')
				)
			);
		}
	}
?>