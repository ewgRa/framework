<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ModelTestCase extends FrameworkTestCase
	{
		public function testData()
		{
			$model = Model::create();
			
			$model->setData(array('a' => 'b'));
			$model->set('c', 'd');
			$model->set('e', 'f');
			$model->drop('e');
			$model->append('h');
			$model->merge(array('g' => 'g1'));
			$model->mergeModel(Model::create()->set('j', 'k'));

			$this->assertTrue($model->has('j'));
			
			$this->assertSame(
				$model->getData(),
				array(
					'a' => 'b',
					'c' => 'd',
					0 => 'h',
					'g' => 'g1',
					'j' => 'k'
				)
			);
		}

		public function testGetMissing()
		{
			$model = Model::create();

			try {
				$model->get('a');
				$this->fail();
			} catch (MissingArgumentException $e) {
				
			}
		}
	}
?>