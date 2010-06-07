<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveYamlTestCase extends FrameworkTestCase
	{
		public function testImport()
		{
			$data = 'testData: testValue';
			
			$primitive =
				PrimitiveYaml::create('testPrimitive')->
				import(array('testPrimitive' => $data));
			
			$this->assertSame(
				array('testData' => 'testValue'),
				$primitive->getValue()
			);
		}
	}
?>