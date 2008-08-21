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
		
		public function getViewModel()
		{
			$result = null;
			
			if($this->view instanceof XSLTView)
			{
				$projectOptions = Config::me()->getOption('project');
		
				$result = new ExtendedDomDocument(
					'1.0',
					$projectOptions['charset']
				);

				$root = $result->createNodeFromArray(
					$this->model,
					'document'
				);

				$result->appendChild($root);
			}
			
			return $result;
		}
		
		public function render()
		{
			return $this->view->transform($this->getViewModel());
		}
	}
?>