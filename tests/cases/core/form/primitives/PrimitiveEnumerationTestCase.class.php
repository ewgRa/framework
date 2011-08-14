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
			$primitive =
				\ewgraFramework\PrimitiveEnumeration::create('testPrimitive')->
				setClass(__NAMESPACE__.'\\PrimitiveEnumerationTestClass')->
				import(array('testPrimitive' => '0'));

			$this->assertTrue($primitive->hasErrors());

			$primitive =
				\ewgraFramework\PrimitiveEnumeration::create('testPrimitive')->
				setClass(__NAMESPACE__.'\\PrimitiveEnumerationTestClass')->
				import(array('testPrimitive' => 'NON_EXISTS_ID'));

			$this->assertTrue($primitive->hasErrors());

			$id = PrimitiveEnumerationTestClass::TEST;

			$primitive =
				\ewgraFramework\PrimitiveEnumeration::create('testPrimitive')->
				setClass(__NAMESPACE__.'\\PrimitiveEnumerationTestClass')->
				import(array('testPrimitive' => $id));

			$this->assertSame($id, $primitive->getValue()->getId());
		}
	}
?>