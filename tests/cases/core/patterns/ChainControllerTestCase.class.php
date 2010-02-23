<?php
	/* $Id$ */

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
				ModelAndView::create()->
				setModel(
					Model::create()->
					set('callStack', array())
				);
				
			$chain->handleRequest(HttpRequest::create(), $mav);
			
			$this->assertSame(
				$mav->getModel()->get('callStack'),
				array('TestChainController1', 'TestChainController2')
			);
		}

		public function testOuter()
		{
			$inner = new TestChainController2();
			$chain = new TestChainController1($inner);
				
			$this->assertTrue($inner->hasOuter());
			
			$this->assertSame($inner->getOuter(), $chain);
		}
	}
	
	class BaseTestChainController extends ChainController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$callStack = $mav->getModel()->get('callStack');
			$callStack[] = get_class($this);

			$mav->getModel()->set('callStack', $callStack);
			
			return parent::handleRequest($request, $mav);
		}
	}

	final class TestChainController1 extends BaseTestChainController
	{
	}

	final class TestChainController2 extends BaseTestChainController
	{
	}
?>