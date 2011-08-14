<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ActionChainControllerTestCase extends FrameworkTestCase
	{
		public function testRequest()
		{
			$controller = new TestActionChainController1();

			$controller->setRequestAction('action');

			$controller->handleRequest(
				\ewgraFramework\HttpRequest::create()->
				setGetVar(
					TestActionChainController1::getActionScopeKey(),
					'breakChainAction'
				),
				\ewgraFramework\ModelAndView::create()->
				setModel(\ewgraFramework\Model::create())
			);

			$this->assertSame(
				'action',
				$controller->getAction()
			);
		}

		public function testActionScopeKey()
		{
			$chain = new TestActionChainController3();

			$this->assertSame(
				'ewgraFrameworktestsTestActionChainController3Action',
				TestActionChainController3::getActionScopeKey()
			);
		}

		public function testActionContinueChain()
		{
			$chain =
				new TestActionChainController1(
					new TestActionChainController2()
				);

			$mav =
				\ewgraFramework\ModelAndView::create()->
				setModel(
					\ewgraFramework\Model::create()
				);

			$chain->handleRequest(\ewgraFramework\HttpRequest::create(), $mav);

			$this->assertTrue($mav->getModel()->has('controller1'));
			$this->assertTrue($mav->getModel()->has('controller2'));
		}

		public function testActionBreakChain()
		{
			$chain =
				new TestActionChainController1(
					new TestActionChainController2()
				);

			$mav =
				\ewgraFramework\ModelAndView::create()->
				setModel(
					\ewgraFramework\Model::create()
				);

			$chain->handleRequest(
				\ewgraFramework\HttpRequest::create()->
				setGetVar(
					TestActionChainController1::getActionScopeKey(),
					'breakChainAction'
				),
				$mav
			);

			$this->assertTrue($mav->getModel()->has('controllerBreak1'));
			$this->assertFalse($mav->getModel()->has('controller2'));
		}

		public function testExtendAction()
		{
			$chain =
				new TestActionChainController1(
					new TestActionChainControllerExtendAction()
				);

			$mav =
				\ewgraFramework\ModelAndView::create()->
				setModel(
					\ewgraFramework\Model::create()
				);

			$chain->handleRequest(\ewgraFramework\HttpRequest::create(), $mav);

			$this->assertTrue($mav->getModel()->has('controller1'));
			$this->assertTrue($mav->getModel()->has('extendedController1'));
		}
	}

	final class TestActionChainController3 extends \ewgraFramework\ActionChainController
	{
	}

	class TestActionChainController1 extends \ewgraFramework\ActionChainController
	{
		public function getAction()
		{
			return parent::getAction();
		}

		public static function getActionScopeKey()
		{
			return 'action';
		}

		/**
		 * @return CatalogController
		 */
		public function __construct(\ewgraFramework\ChainController $controller = null)
		{
			$this->
				addAction('action', 'action')->
				addAction('breakChainAction', 'breakChainAction')->
				setDefaultAction('action');

			parent::__construct($controller);
		}

		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function action(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$mav->getModel()->set('controller1', true);

			return $this->continueAction($request, $mav);
		}

		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		protected function continueAction(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			return parent::continueHandleRequest($request, $mav);
		}

		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function breakChainAction(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$mav->getModel()->set('controllerBreak1', true);

			return $mav;
		}
	}

	final class TestActionChainControllerExtendAction extends TestActionChainController1
	{
		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function continueAction(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$mav->getModel()->set('extendedController1', true);

			return parent::continueAction($request, $mav);
		}
	}

	final class TestActionChainController2 extends \ewgraFramework\ChainController
	{
		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$mav->getModel()->set('controller2', true);

			return parent::handleRequest($request, $mav);
		}
	}
?>