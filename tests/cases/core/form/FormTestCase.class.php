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
				$form->getPrimitive('testPrimitive')->getValue(),
				$data['testPrimitive']
			);
		}

		public function testImported()
		{
			$form =
				Form::create()->
				addPrimitive(
					PrimitiveString::create('testPrimitive')
				);
				
			$form->import(array());
			
			$this->assertFalse($form->isImported());

			$form->import(array('a' => 'b'));
			
			$this->assertFalse($form->hasErrors());
			
			$this->assertTrue($form->isImported());
			
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
				$form->getErrors(),
				array('testPrimitive' => array(PrimitiveErrors::MISSING))
			);
			
			$this->assertTrue(
				$form->
					getPrimitive('testPrimitive')->
					hasError(PrimitiveErrors::MISSING)
			);
			
			$this->assertSame(
				$form->getPrimitive('testPrimitive')->
				getErrorLabel(PrimitiveErrors::MISSING),
				'missing primitive'
			);
		}
	}
?>