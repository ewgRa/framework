<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class ActionChainController extends ChainController
	{
		private $requestAction = null;
		private $defaultAction = null;
		private $actionList = array();
		
		/**
		 * @return ActionChainController
		 */
		public function setRequestAction($requestAction)
		{
			$this->requestAction = $requestAction;
			return $this;
		}
		
		public function getRequestAction()
		{
			return $this->requestAction;
		}
				
		/**
		 * @return ActionChainController
		 */
		public function setDefaultAction($defaultAction)
		{
			$this->defaultAction = $defaultAction;
			return $this;
		}
		
		public function getDefaultAction()
		{
			return $this->defaultAction;
		}
		
		/**
		 * @return ActionChainController
		 */
		public function addAction($action, $function)
		{
			$this->actionList[$action] = $function;
			return $this;
		}
		
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$action = $this->getRequestAction();
			
			if (!$action) {
				$form = 
					Form::create()->
					addPrimitive(PrimitiveString::create('action'));
				
				$form->import($request->getPost() + $request->getGet());
				
				$action = $form->getValue('action');
			}
			
			if (!$action)
				$action = $this->getDefaultAction();
			
			Assert::isNotNull($action, 'action is not defined');
			
			if(!isset($this->actionList[$action]))
				throw BadRequestException::create();
			
			return $this->{$this->actionList[$action]}($request, $mav);
		}

		/**
		 * @return ModelAndView
		 */
		public function continueHandleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			return parent::handleRequest($request, $mav);
		}
	}
?>