<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MetaBuilderTestCase extends FrameworkTestCase
	{
		public static function setUpBeforeClass()
		{
			self::tearDownAfterClass();

			$command = 'php '.FRAMEWORK_DIR.'/meta/build.php --base-dir '.__DIR__;
			`$command`;
			include __DIR__.'/auto.config.php';
		}

		public static function tearDownAfterClass()
		{
			$file = \ewgraFramework\File::create()->setPath(__DIR__.'/auto.config.php');

			if ($file->isExists())
				$file->delete();

			$dir = \ewgraFramework\Dir::create()->setPath(__DIR__.'/classes');

			if ($dir->isExists())
				$dir->delete();
		}

		public function testMysqlDA()
		{
			$this->fail();
		}

		public function testPostgresqlDA()
		{
			$this->fail();
		}
	}
?>