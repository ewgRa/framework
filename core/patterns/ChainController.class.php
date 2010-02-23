<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class ChainController
	{
		private $inner = null;
		private $outer = null;
		
		/**
		 * @return ChainController
		 */
		public function __construct(ChainController $controller = null)
		{
			if ($controller)
				$this->setInner($controller);
				
			return $this;
		}
		
		/**
		 * @return ChainController
		 */
		public function setInner(ChainController $controller)
		{
			$this->inner = $controller;
			
			if ($controller->getOuter() != $this)
				$controller->setOuter($this);
			
			return $this;
		}
		
		/**
		 * @return ChainController
		 */
		public function getInner()
		{
			return $this->inner;
		}

		public function hasInner()
		{
			return !is_null($this->inner);
		}
		
		/**
		 * @return ChainController
		 */
		public function setOuter(ChainController $controller)
		{
			$this->outer = $controller;

			if ($controller->getInner() != $this)
				$controller->setInner($this);
			
			return $this;
		}
		
		/**
		 * @return ChainController
		 */
		public function getOuter()
		{
			return $this->outer;
		}
		
		public function hasOuter()
		{
			return !is_null($this->outer);
		}
		
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			return
				$this->hasInner()
					? $this->getInner()->handleRequest($request, $mav)
					: $mav;
		}
	}
?>