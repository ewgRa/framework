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
	}
?>