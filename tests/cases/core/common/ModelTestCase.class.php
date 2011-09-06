<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ModelTestCase extends FrameworkTestCase
	{
		public function testData()
		{
			$model = \ewgraFramework\Model::create();

			$model->setData(array('a' => 'b'));
			$model->set('c', 'd');
			$model->set('e', 'f');
			$model->drop('e');
			$model->append('h');
			$model->merge(array('g' => 'g1'));
			$model->mergeModel(\ewgraFramework\Model::create()->set('j', 'k'));

			$this->assertTrue($model->has('j'));

			$this->assertSame(
				array(
					'a' => 'b',
					'c' => 'd',
					0 => 'h',
					'g' => 'g1',
					'j' => 'k'
				),
				$model->getData()
			);
		}

		public function testGetMissing()
		{
			$model = \ewgraFramework\Model::create();

			try {
				$model->get('a');
				$this->fail();
			} catch (\ewgraFramework\MissingArgumentException $e) {
				# all good
			}
		}

		public function testNullValue()
		{
			$model = \ewgraFramework\Model::create();

			$model->set('a', null);

			$this->assertTrue($model->has('a'));
		}
	}
?>