<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveObjectTestCase extends FrameworkTestCase
	{
		public function testImport()
		{
			$id = 1;

			$primitive =
				\ewgraFramework\PrimitiveObject::create('testPrimitive')->
				setClass(__NAMESPACE__.'\\PrimitiveObjectTestObject')->
				import(array('testPrimitive' => $id));

			$this->assertSame($id, $primitive->getValue()->getId());
		}
	}
?>