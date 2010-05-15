<?php
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
				PrimitiveObject::create('testPrimitive')->
				setClass('PrimitiveObjectTestObject')->
				import(array('testPrimitive' => $id));
			
			$this->assertSame($primitive->getValue()->getId(), $id);
		}
	}
?>