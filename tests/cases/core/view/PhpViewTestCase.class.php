<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class PhpViewTestCase extends FrameworkTestCase
	{
		public function testTransform()
		{
			$viewResult =
				$this->createView()->
				transform(Model::create()->set('data', 'testData'));
			
			$this->assertSame($viewResult, 'testDatatestData');
		}

		public function testToString()
		{
			$this->assertSame(
				$this->createView()->toString(),
				File::create()->
				setPath(dirname(__FILE__).'/renderPhpView.php')->
				getContent()
			);
		}
		
		public function testIncludeFile()
		{
			ob_start();
			
			PhpView::includeFile(
				dirname(__FILE__).'/renderPhpView.php',
				Model::create()->set('data', 'testData')
			);
			
			$content = ob_get_clean();
			
			$this->assertSame($content, 'testDatatestData');
		}
		
		private function createView()
		{
			return
				PhpView::create()->
				loadLayout(
					File::create()->
					setPath(dirname(__FILE__).'/renderPhpView.php')
				);
		}
	}
?>