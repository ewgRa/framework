<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveEnumerationTestCase extends FrameworkTestCase
	{
		public function testImport()
		{
			$id = 1;
			
			$primitive =
				PrimitiveEnumeration::create('testPrimitive')->
				setClass('PrimitiveEnumerationTestClass')->
				import(array('testPrimitive' => $id));
			
			$this->assertSame($primitive->getValue()->getId(), $id);
		}
	}
?>