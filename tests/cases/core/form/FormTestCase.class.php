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
			$data = array('testPrimitive' => 'string primitive value');

			$form =
				\ewgraFramework\Form::create()->
				addPrimitive(
					\ewgraFramework\PrimitiveString::create('testPrimitive')->
					setRequired()
				);

			$form->import($data);

			$this->assertSame(
				$data['testPrimitive'],
				$form->getPrimitive('testPrimitive')->getValue()
			);

			$moreData = array('notPrimitiveKey' => 'notPrimitiveKeyValue');

			$form->importMore($moreData);

			$this->assertFalse($form->hasErrors());
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

			$form->clean('testPrimitive');

			$this->assertFalse($form->hasErrors());
		}

		public function testErrors()
		{
			$form =
				\ewgraFramework\Form::create()->
				addPrimitive(
					\ewgraFramework\PrimitiveString::create('testPrimitive')->
					setDefaultValue('default value')->
					setRequired()->
					setMissingErrorLabel('missing primitive')
				);

			$form->import(array('testPrimitive' => ''));

			$this->assertTrue($form->hasErrors());

			$this->assertSame(
				'default value',
				$form->getSafeValue('testPrimitive')
			);

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

			$form->dropPrimitive('testPrimitive');
			$this->assertFalse($form->hasPrimitive('testPrimitive'));

			$this->assertFalse($form->hasErrors());


			$wrongErrorLabel = 'Wrong email!';

			$primitive =
				\ewgraFramework\PrimitiveEmail::create('testPrimitive')->
				setWrongErrorLabel('Wrong email!')->
				import(array('testPrimitive' => 'ewgraf@gmail'));

			$this->assertTrue($primitive->hasErrors());

			$this->assertSame(
				$wrongErrorLabel,
				$primitive->getErrorLabel($primitive::WRONG_ERROR)
			);

			$missingErrorLabel = 'Enter email, please';

			$primitive->
				setErrorLabel($primitive::MISSING_ERROR, $missingErrorLabel)->
				setRequired();

			$primitive->import(array());

			$this->assertTrue($primitive->hasErrors());

			$this->assertSame(
				$missingErrorLabel,
				$primitive->getErrorLabel($primitive::MISSING_ERROR)
			);
		}
	}
?>