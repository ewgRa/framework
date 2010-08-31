<?php
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
				Form::create()->
				addPrimitive(PrimitiveString::create('testPrimitive'));
				
			$form->import($data);
			
			$this->assertSame(
				$data['testPrimitive'],
				$form->getPrimitive('testPrimitive')->getValue()
			);
		}

		public function testImported()
		{
			$form =
				Form::create()->
				addPrimitive(
					PrimitiveString::create('testPrimitive')
				);
				
			$form->import(array('a' => 'b'));
			
			$this->assertFalse($form->hasErrors());
			
			$form->getPrimitive('testPrimitive')->
				addError(PrimitiveErrors::MISSING);

			$this->assertTrue($form->hasErrors());
		}

		public function testErrors()
		{
			$form =
				Form::create()->
				addPrimitive(
					PrimitiveString::create('testPrimitive')->
					setRequired()->
					setErrorLabel(
						PrimitiveErrors::MISSING,
						'missing primitive'
					)
				);
				
			$form->import(array('testPrimitive' => ''));
			
			$this->assertTrue($form->hasErrors());
			
			$this->assertSame(
				array('testPrimitive' => array(PrimitiveErrors::MISSING)),
				$form->getErrors()
			);
			
			$this->assertTrue(
				$form->
					getPrimitive('testPrimitive')->
					hasError(PrimitiveErrors::MISSING)
			);
			
			$this->assertSame(
				'missing primitive',
				$form->getPrimitive('testPrimitive')->
				getErrorLabel(PrimitiveErrors::MISSING)
			);
		}
	}
?>