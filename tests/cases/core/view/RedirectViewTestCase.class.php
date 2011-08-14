<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class RedirectViewTestCase extends FrameworkTestCase
	{
		public function testTransform()
		{
			try {
				$viewResult =
					$this->createView()->
					transform(
						\ewgraFramework\Model::create()->set('data', 'testData')
					);

				$this->fail();
			} catch (\ewgraFramework\UnimplementedCodeException $e) {
				# good
			}
		}

		public function testToString()
		{
			$this->assertSame('/baobab', $this->createView()->toString());
		}

		private function createView()
		{
			return
				\ewgraFramework\RedirectView::create()->
				setUrl(\ewgraFramework\HttpUrl::create()->setPath('/baobab'));
		}
	}
?>