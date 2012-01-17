<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveStringTestCase extends FrameworkTestCase
	{
		public function testImport()
		{
			$data = rand();

			$primitive =
				\ewgraFramework\PrimitiveString::create('testPrimitive')->
				import(array('testPrimitive' => $data));

			$this->assertSame($data, $primitive->getRawValue());
			$this->assertSame((string)$data, $primitive->getValue());

			$data = 0;

			$primitive =
				\ewgraFramework\PrimitiveString::create('testPrimitive')->
				import(array('testPrimitive' => $data));

			$this->assertSame($data, $primitive->getRawValue());
			$this->assertSame((string)$data, $primitive->getValue());
		}

		public function testRange()
		{
			$data = 'aaaa';

			$primitive =
				\ewgraFramework\PrimitiveString::create('testPrimitive')->
				setMin(5)->
				import(array('testPrimitive' => $data));

			$this->assertTrue($primitive->hasError($primitive::WRONG_ERROR));

			$primitive =
				\ewgraFramework\PrimitiveString::create('testPrimitive')->
				setMax(5)->
				import(array('testPrimitive' => $data));

			$this->assertFalse($primitive->hasErrors());

			$primitive =
				\ewgraFramework\PrimitiveString::create('testPrimitive')->
				setMax(5)->
				import(array('testPrimitive' => 'aaaaaa'));

			$this->assertTrue($primitive->hasError($primitive::WRONG_ERROR));
		}
	}
?>