<?php
	namespace ewgraFramework\tests;

	// @codeCoverageIgnoreStart

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class AllTestSuite extends \PHPUnit_Framework_TestSuite
	{
		public static function suite()
		{
			return new self;
		}

		public function setUp()
		{
			$cmd = 'find '.CASES_DIR.' | grep TestCase.class.php';

			foreach(explode(PHP_EOL, trim(`$cmd`)) as $file)
				$this->addTestFile($file);
		}
	}

	// @codeCoverageIgnoreEnd
?>