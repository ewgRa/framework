<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveBooleanTestCase extends FrameworkTestCase
	{
		public function testImport()
		{
			$data = array('1', 1, true, 'true');

			$primitive =
				\ewgraFramework\PrimitiveBoolean::create('testPrimitive')->
				setRequired();

			foreach ($data as $value) {
				$primitive->clean()->import(array('testPrimitive' => $value));

				$this->assertFalse($primitive->hasErrors());
				$this->assertTrue($primitive->getValue());
			}

			$primitive->clean()->import(array('testPrimitive' => ''));

			$this->assertTrue($primitive->hasErrors());

			$primitive->clean()->import(array('testPrimitive' => '0'));

			$this->assertFalse($primitive->getValue());
		}
	}
?>