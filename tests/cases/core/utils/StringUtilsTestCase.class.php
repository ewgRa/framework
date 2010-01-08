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
	}
?>