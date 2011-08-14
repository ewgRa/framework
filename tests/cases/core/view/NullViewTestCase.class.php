<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NullViewTestCase extends FrameworkTestCase
	{
		public function testTransform()
		{
			$viewResult =
				$this->createView()->
				transform(\ewgraFramework\Model::create()->set('data', 'testData'));

			$this->assertSame(null, $viewResult);
		}

		public function testToString()
		{
			$this->assertSame(null, $this->createView()->toString());
		}

		private function createView()
		{
			return \ewgraFramework\NullView::create();
		}
	}
?>