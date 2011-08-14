<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveYamlTestCase extends FrameworkTestCase
	{
		public function testImport()
		{
			$primitive =
				\ewgraFramework\PrimitiveYaml::create('testPrimitive')->
				import(array('testPrimitive' => '0'));

			$this->assertTrue($primitive->hasErrors());

			$data = 'testData: testValue';

			$primitive =
				\ewgraFramework\PrimitiveYaml::create('testPrimitive')->
				import(array('testPrimitive' => $data));

			$this->assertSame(
				array('testData' => 'testValue'),
				$primitive->getValue()
			);
		}
	}
?>