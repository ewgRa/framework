<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class XsltViewTestCase extends FrameworkTestCase
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
			
			$fileName =
				TMP_DIR . DIRECTORY_SEPARATOR.'renderXsltView'.rand().'.xsl';
			
			file_put_contents($fileName, $view->toString());
			
			$viewResult =
				$view->transform(Model::create()->set('data', 'testData'));
			
			$view->loadLayout(File::create()->setPath($fileName));
			
			$viewResultSame =
				$view->transform(
					Model::create()->set('data', 'testData')
				);

			unlink($fileName);
				
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