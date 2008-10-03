<?php
	/* $Id$ */

	// FIXME: tested?
	class ModelAndView
	{
		private $view = null;
		private $model = null;
		
		/**
		 * @return ModelAndView
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return ModelAndView
		 */
		public function setView(BaseView $view)
		{
			$this->view = $view;
			return $this;
		}
		
		public function hasView()
		{
			return !is_null($this->view);
		}
		
		/**
		 * @return ModelAndView
		 */
		public function setModel($model)
		{
			$this->model = $model;
			return $this;
		}
		
		public function getModel()
		{
			return $this->model;
		}
		
		public function render()
		{
			if(!$this->hasView())
				throw new Exception('no view for render');

			return $this->view->transform($this->getModel());
		}
	}
?>