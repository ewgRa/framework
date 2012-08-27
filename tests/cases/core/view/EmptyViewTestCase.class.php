<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class EmptyViewTestCase extends FrameworkTestCase
	{
		public function testTransform()
		{
			$viewResult =
				$this->createView()->
				transform(\ewgraFramework\Model::create()->set('data', 'testData'));

			$this->assertSame('', $viewResult);
		}

		public function testToString()
		{
			$this->assertSame(
				'',
				$this->createView()->toString()
			);
		}

		private function createView()
		{
			return \ewgraFramework\EmptyView::create();
		}
	}
?>