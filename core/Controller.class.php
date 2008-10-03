<?php
	/* $Id$ */

	abstract class Controller
	{
		private $cacheTicket = null;
		private $view = null;
		
		public function hasCacheTicket()
		{
			return !is_null($this->cacheTicket);
		}
		
		public function getCacheTicket()
		{
			return $this->cacheTicket;
		}
		
		protected function setCacheTicket(CacheTicket $cacheTicket)
		{
			$this->cacheTicket = $cacheTicket;
			return $this;
		}
		
		public function setView(BaseView $view = null)
		{
			$this->view = $view;
			return $this;
		}
		
		public function getView()
		{
			return $this->view;
		}
		
		abstract public function getModel();

		public function importSettings($settings)
		{
			return $this;
		}
		
		public function getRenderedModel()
		{
			$renderedModel = null;
			
			if($this->hasCacheTicket())
			{
				$this->getCacheTicket()->restoreData();
				
				if($this->getCacheTicket()->isExpired())
				{
					$renderedModel = $this->renderModel();
					
					$this->getCacheTicket()->
						setData($renderedModel)->
						storeData();
				}
				else
					$renderedModel = $this->getCacheTicket()->getData();
			}

			if(is_null($renderedModel))
				$renderedModel = $this->renderModel();
			
			return
				$renderedModel;
		}
		
		private function renderModel()
		{
			$view	= $this->getView();
			$model	= $this->getModel();
			
			return $view
				? ModelAndView::create()->
					setModel($model)->
					setView($view)->
					render()
				: null;
		}
	}
?>