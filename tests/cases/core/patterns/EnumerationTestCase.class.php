<?php
	/* $Id$ */

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
			} catch (MissingArgumentException $e) {
			}
		}

		public function testCommon()
		{
			$enumeration = EnumerationTest::create(1);
			
			$this->assertSame($enumeration->getName(), 'test');
			
			$this->assertSame((string)$enumeration, $enumeration->getName());
			
			$this->assertEquals(
				$enumeration->getList(),
				array(
					1 => $enumeration,
					2 => EnumerationTest::create(2)
				)
			);

			$this->assertEquals(
				$enumeration->getNames(),
				array(1 => 'test', 2 => 'test2')
			);
		}
	}
?>