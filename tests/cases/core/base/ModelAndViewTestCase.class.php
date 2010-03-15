<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ModelAndViewTestCase extends FrameworkTestCase
	{
		public function testRender()
		{
			$mav = ModelAndView::create();
			
			$mav->setView(
				PhpView::create()->
				loadLayout(
					File::create()->
					setPath(dirname(__FILE__).'/../view/renderPhpView.php')
				)
			);
			
			$mav->setModel(Model::create()->set('data', 'value'));
			
			$this->assertSame($mav->render(), 'value');
		}
	}
?>