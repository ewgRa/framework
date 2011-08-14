<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveArrayTestCase extends FrameworkTestCase
	{
		public function testImport()
		{
			try {
				\ewgraFramework\PrimitiveArray::create('testPrimitive')->
				import(array('testPrimitive' => null));
			} catch (\ewgraFramework\WrongArgumentException $e) {
				$this->fail();
			}

			try {
				\ewgraFramework\PrimitiveArray::create('testPrimitive')->
				import(array('testPrimitive' => '0'));

				$this->fail();
			} catch (\ewgraFramework\WrongArgumentException $e) {
				# good
			}

			$data = array(rand(), rand());

			$primitive =
				\ewgraFramework\PrimitiveArray::create('testPrimitive')->
				import(array('testPrimitive' => $data));

			$this->assertSame($data, $primitive->getRawValue());
			$this->assertSame($data, $primitive->getValue());

			$data = array('0');

			$primitive =
				\ewgraFramework\PrimitiveArray::create('testPrimitive')->
				import(array('testPrimitive' => $data));

			$this->assertSame($data, $primitive->getRawValue());
			$this->assertSame($data, $primitive->getValue());
		}
	}
?>