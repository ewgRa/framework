<?php
	namespace ewgraFramework\tests;
	
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
				\ewgraFramework\PrimitiveEnumeration::create('testPrimitive')->
				setClass(__NAMESPACE__.'\\PrimitiveEnumerationTestClass')->
				import(array('testPrimitive' => $id));
			
			$this->assertSame($id, $primitive->getValue()->getId());
		}
	}
?>