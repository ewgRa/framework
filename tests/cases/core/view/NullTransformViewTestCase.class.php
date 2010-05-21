<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NullTransformViewTestCase extends FrameworkTestCase
	{
		public function testTransform()
		{
			$model = Model::create()->set('data', 'testData');
		
			$viewResult =
				$this->createView()->
				transform($model);
			
			$this->assertSame($viewResult, $model);
		}

		public function testToString()
		{
			$this->assertSame(
				$this->createView()->toString(),
				null
			);
		}
		
		private function createView()
		{
			return NullTransformView::create();
		}
	}
?>