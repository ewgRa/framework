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
			$mysqlTest = new MetaBuilderMysqlTest();
			$mysqlTest->setUp();

			$da = TestDA::me()->setPool($mysqlTest->getInstance());

			$object = Test::create()->setField(2);

			$object->da()->insert($object);

			$this->assertSame(1, $object->getId());

			$object->setField(3);
			$object->da()->save($object);

			$object = $da->getById(1);

			$this->assertSame('3', $object->getField());

			$object->da()->delete($object);

			$this->assertNull($da->getById(1));
		}

		public function testPostgresqlDA()
		{
			$postgresqlTest = new MetaBuilderPostgresqlTest();
			$postgresqlTest->setUp();

			$da = TestDA::me()->setPool($postgresqlTest->getInstance());

			$object = Test::create()->setField(2);

			$object->da()->insert($object);

			$this->assertSame('1', $object->getId());

			$object->setField(3);
			$object->da()->save($object);

			$object = $da->getById(1);

			$this->assertSame('3', $object->getField());

			$object->da()->delete($object);

			$this->assertNull($da->getById(1));
		}
	}
?>