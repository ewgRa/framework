<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class XsltViewTestCase extends FrameworkTestCase
	{
		public function testTransform()
		{
			$viewResult =
				$this->createView()->
				transform(\ewgraFramework\Model::create()->set('data', 'testData'));

			$this->assertSame('testData', $viewResult);
		}

		public function testToString()
		{
			$view = $this->createView();

			$file =
				\ewgraFramework\File::create()->setPath(
					TMP_DIR.DIRECTORY_SEPARATOR.'renderXsltView'.rand().'.xsl'
				);

			$file->setContent($view->toString());

			$model = \ewgraFramework\Model::create()->set('data', 'testData');

			$viewResult = $view->transform($model);

			$view->loadLayout($file);

			$viewResultSame = $view->transform($model);

			$file->delete();

			$this->assertSame($viewResult, $viewResultSame);
		}

		private function createView()
		{
			return
				\ewgraFramework\XsltView::create()->
				setCharset('utf8')->
				setVersion('1.0')->
				loadLayout(
					\ewgraFramework\File::create()->
					setPath(dirname(__FILE__).'/renderXsltView.xsl')
				);
		}
	}
?>