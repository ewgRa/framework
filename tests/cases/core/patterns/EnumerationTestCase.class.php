<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class EnumerationTestCase extends FrameworkTestCase
	{
		public function testCreate()
		{
			try {
				EnumerationTest::create('noId'.rand());
				$this->fail();
			} catch (\ewgraFramework\MissingArgumentException $e) {
				# all good
			}
		}

		public function testCommon()
		{
			$enumeration = EnumerationTest::create(1);

			$this->assertSame('test', $enumeration->getName());

			$this->assertSame($enumeration->getName(), (string)$enumeration);

			$this->assertEquals(
				array(
					1 => $enumeration,
					2 => EnumerationTest::create(2)
				),
				$enumeration->getList()
			);

			$this->assertEquals(
				array(1 => 'test', 2 => 'test2'),
				$enumeration->getNames()
			);
		}
	}
?>