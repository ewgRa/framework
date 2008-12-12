<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
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
			if($controller)
			{
				$this->setInner($controller);
				$controller->setOuter($this);
			}
				
			return $this;
		}
		
		/**
		 * @return ChainController
		 */
		public function setInner(ChainController $controller)
		{
			$this->inner = $controller;
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
		public function handleRequest(ModelAndView $mav)
		{
			return
				$this->hasInner()
					? $this->getInner()->handleRequest($mav)
					: $mav;
		}
	}
?>