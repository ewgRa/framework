<?php
	/* $Id$ */

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
			
			$this->assertTrue($form->isImported());
		}

		public function testErrors()
		{
			$form =
				Form::create()->
				addPrimitive(
					PrimitiveString::create('testPrimitive')->setRequired()
				);
				
			$form->import(array('testPrimitive' => ''));
			
			$this->assertSame(
				$form->getErrors(),
				array('testPrimitive' => array('missing'))
			);
		}
	}
?>