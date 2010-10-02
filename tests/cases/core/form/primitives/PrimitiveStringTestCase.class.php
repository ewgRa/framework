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
		}
	}
?>