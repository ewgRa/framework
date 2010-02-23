<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveArrayTestCase extends FrameworkTestCase
	{
		public function testImport()
		{
			$data = array(rand(), rand());
			
			$primitive =
				PrimitiveArray::create('testPrimitive')->
				import(array('testPrimitive' => $data));
			
			$this->assertSame($data, $primitive->getRawValue());
			$this->assertSame($data, $primitive->getValue());
		}
	}
?>