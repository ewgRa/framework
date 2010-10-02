<?php
	namespace ewgraFramework\tests;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NullTransformViewTestCase extends FrameworkTestCase
	{
		public function testTransform()
		{
			$model = \ewgraFramework\Model::create()->set('data', 'testData');
		
			$viewResult =
				$this->createView()->
				transform($model);
			
			$this->assertSame($model, $viewResult);
		}

		public function testToString()
		{
			$this->assertSame(
				null,
				$this->createView()->toString()
			);
		}
		
		private function createView()
		{
			return \ewgraFramework\NullTransformView::create();
		}
	}
?>