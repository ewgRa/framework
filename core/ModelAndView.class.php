<?php
	/* $Id$ */

	// FIXME: tested?
	class ModelAndView
	{
		private $view = null;
		private $model = null;
		
		public static function create()
		{
			return new self;
		}
		
		public function setView($view)
		{
			$this->view = $view;
			return $this;
		}
		
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
			if($this->view)
				return $this->view->transform($this->getModel());
				
			return null;
		}
	}
?>