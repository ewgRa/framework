<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveEmailTestCase extends FrameworkTestCase
	{
		public function testImport()
		{
			$primitive =
				\ewgraFramework\PrimitiveEmail::create('testPrimitive')->
				import(array('testPrimitive' => '0'));

			$this->assertTrue($primitive->hasErrors());

			$primitive =
				\ewgraFramework\PrimitiveEmail::create('testPrimitive')->
				import(array('testPrimitive' => 'ewgraf@gmail.com'));

			$this->assertFalse($primitive->hasErrors());

			$wrongErrorLabel = 'Wrong email!';

			$primitive =
				\ewgraFramework\PrimitiveEmail::create('testPrimitive')->
				import(array('testPrimitive' => 'ewgraf@gmail'));

			$this->assertTrue($primitive->hasErrors());
		}
	}
?>