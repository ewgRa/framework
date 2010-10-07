<?php
	namespace ewgraFramework\tests;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class FormTestCase extends FrameworkTestCase
	{
		public function testImport()
		{
			$data = array(
				'testPrimitive' => 'string primitive value'
			);
			
			$form =
				\ewgraFramework\Form::create()->
				addPrimitive(\ewgraFramework\PrimitiveString::create('testPrimitive'));
				
			$form->import($data);
			
			$this->assertSame(
				$data['testPrimitive'],
				$form->getPrimitive('testPrimitive')->getValue()
			);
		}

		public function testImported()
		{
			$form =
				\ewgraFramework\Form::create()->
				addPrimitive(
					\ewgraFramework\PrimitiveString::create('testPrimitive')
				);
				
			$form->import(array('a' => 'b'));
			
			$this->assertFalse($form->hasErrors());
			
			$form->getPrimitive('testPrimitive')->markMissing();

			$this->assertTrue($form->hasErrors());
		}

		public function testErrors()
		{
			$form =
				\ewgraFramework\Form::create()->
				addPrimitive(
					\ewgraFramework\PrimitiveString::create('testPrimitive')->
					setRequired()->
					setMissingErrorLabel('missing primitive')
				);
				
			$form->import(array('testPrimitive' => ''));
			
			$this->assertTrue($form->hasErrors());
			
			$this->assertSame(
				array(
					'testPrimitive' => 
						array(\ewgraFramework\BasePrimitive::MISSING_ERROR)
				),
				$form->getErrors()
			);
			
			$this->assertTrue(
				$form->
					getPrimitive('testPrimitive')->
					hasError(\ewgraFramework\BasePrimitive::MISSING_ERROR)
			);
			
			$this->assertSame(
				'missing primitive',
				$form->getPrimitive('testPrimitive')->
				getErrorLabel(\ewgraFramework\BasePrimitive::MISSING_ERROR)
			);
		}
	}
?>