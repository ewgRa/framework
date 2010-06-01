<?php
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
				transform(Model::create()->set('data', 'testData'));
			
			$this->assertSame($viewResult, 'testData');
		}

		public function testToString()
		{
			$view = $this->createView();
			
			$file =
				File::create()->setPath(
					TMP_DIR.DIRECTORY_SEPARATOR.'renderXsltView'.rand().'.xsl'
				);
			
			$file->setContent($view->toString());
			
			$model = Model::create()->set('data', 'testData');
			
			$viewResult = $view->transform($model);
			
			$view->loadLayout($file);
			
			$viewResultSame = $view->transform($model);

			$file->delete();
				
			$this->assertSame($viewResult, $viewResultSame);
		}
		
		private function createView()
		{
			return
				XsltView::create()->
				setCharset('utf8')->
				setVersion('1.0')->
				loadLayout(
					File::create()->
					setPath(dirname(__FILE__).'/renderXsltView.xsl')
				);
		}
	}
?>