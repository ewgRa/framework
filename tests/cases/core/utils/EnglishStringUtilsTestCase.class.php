<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class EnglishStringUtilsTestCase extends FrameworkTestCase
	{
		public function testSeparateByUpperKey()
		{
			$this->assertEquals(
				'test_string_test',
				\ewgraFramework\EnglishStringUtils::separateByUpperKey('testStringTest')
			);
		}
	}
?>