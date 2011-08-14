<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveObjectListTestCase extends FrameworkTestCase
	{
		public function testImport()
		{
			$id = '0';

			$primitive =
				\ewgraFramework\PrimitiveObjectList::create('testPrimitive')->
				setClass(__NAMESPACE__.'\\PrimitiveObjectTestObject')->
				import(
					array(
						'testPrimitive' =>
							array($id, PrimitiveObjectTestObject::EXISTS_ID)
					)
				);

			$this->assertTrue($primitive->hasErrors());

			$id = PrimitiveObjectTestObject::EXISTS_ID;

			$primitive->clean()->import(array('testPrimitive' => array($id)));

			$this->assertSame(
				\ewgraFramework\ArrayUtils::getObjectIds(
					array(PrimitiveObjectTestObject::create()->setId($id))
				),
				\ewgraFramework\ArrayUtils::getObjectIds($primitive->getValue())
			);
		}
	}
?>