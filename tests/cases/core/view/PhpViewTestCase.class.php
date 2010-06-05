<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PhpViewTestCase extends FrameworkTestCase
	{
		public function testTransform()
		{
			$viewResult =
				$this->createView()->
				transform(Model::create()->set('data', 'testData'));
			
			$this->assertSame('testDatatestData', $viewResult);
		}

		public function testToString()
		{
			$this->assertSame(
				File::create()->
				setPath(dirname(__FILE__).'/renderPhpView.php')->
				getContent(),
				$this->createView()->toString()
			);
		}
		
		public function testIncludeFile()
		{
			ob_start();
			
			PhpView::includeFile(
				dirname(__FILE__).'/renderPhpView.php',
				Model::create()->set('data', 'testData')
			);
			
			$this->assertSame('testDatatestData', ob_get_clean());
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