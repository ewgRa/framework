<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ChainControllerTestCase extends FrameworkTestCase
	{
		public function testHandleRequest()
		{
			$chain =
				new TestChainController1(
					new TestChainController2()
				);

			$mav =
				\ewgraFramework\ModelAndView::create()->
				setModel(
					\ewgraFramework\Model::create()->
					set('callStack', array())
				);

			$chain->handleRequest(\ewgraFramework\HttpRequest::create(), $mav);

			$this->assertSame(
				array(
					__NAMESPACE__.'\\TestChainController1',
					__NAMESPACE__.'\\TestChainController2'
				),
				$mav->getModel()->get('callStack')
			);
		}

		public function testBreakHandleRequest()
		{
			$chain =
				new TestChainController1(
					new TestChainControllerBreakChain(
						new TestChainController2()
					)
				);

			$mav =
				\ewgraFramework\ModelAndView::create()->
				setModel(
					\ewgraFramework\Model::create()->
					set('callStack', array())
				);

			$chain->handleRequest(\ewgraFramework\HttpRequest::create(), $mav);

			$this->assertSame(
				array(
					__NAMESPACE__.'\\TestChainController1',
					__NAMESPACE__.'\\TestChainControllerBreakChain'
				),
				$mav->getModel()->get('callStack')
			);
		}

		public function testChain()
		{
			$inner = new TestChainController2();
			$chain = new TestChainController1($inner);

			$this->assertTrue($inner->hasOuter());

			$this->assertSame($chain, $inner->getOuter());
			$this->assertSame($chain, $inner->getFirstController());
		}
	}

	class BaseTestChainController extends \ewgraFramework\ChainController
	{
		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$this->registerCallback($mav);

			return parent::handleRequest($request, $mav);
		}

		protected function registerCallback(\ewgraFramework\ModelAndView $mav)
		{
			$callStack = $mav->getModel()->get('callStack');
			$callStack[] = get_class($this);

			$mav->getModel()->set('callStack', $callStack);

			return $this;
		}
	}

	final class TestChainController1 extends BaseTestChainController
	{
	}

	final class TestChainController2 extends BaseTestChainController
	{
	}

	final class TestChainControllerBreakChain extends BaseTestChainController
	{
		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$this->registerCallback($mav);

			return $mav;
		}

	}
?>