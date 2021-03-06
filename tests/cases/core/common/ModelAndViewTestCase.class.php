<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ModelAndViewTestCase extends FrameworkTestCase
	{
		public function testRender()
		{
			$mav = \ewgraFramework\ModelAndView::create();

			$view =
				\ewgraFramework\PhpView::create()->
				loadLayout(
					\ewgraFramework\File::create()->
					setPath(dirname(__FILE__).'/../view/renderPhpView.php')
				);

			$mav->setView($view);

			$this->assertSame($mav->getView(), $view);

			$mav->setModel(\ewgraFramework\Model::create()->set('data', 'value'));

			$this->assertSame('valuevalue', $mav->render());
		}
	}
?>