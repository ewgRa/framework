<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	abstract class Controller
	{
		/**
		 * @var CacheTicket
		 */
		private $cacheTicket = null;
		
		/**
		 * @var BaseView
		 */
		private $view		 = null;
		
		/**
		 * @return Model
		 */
		abstract public function getModel(HttpRequest $request);

		public function hasCacheTicket()
		{
			return !is_null($this->cacheTicket);
		}
		
		/**
		 * @return CacheTicket
		 */
		public function getCacheTicket()
		{
			return $this->cacheTicket;
		}
		
		/**
		 * @return Controller
		 */
		protected function setCacheTicket(CacheTicket $cacheTicket)
		{
			$this->cacheTicket = $cacheTicket;
			return $this;
		}
		
		/**
		 * @return Controller
		 */
		public function setView(BaseView $view = null)
		{
			$this->view = $view;
			return $this;
		}
		
		/**
		 * @return BaseView
		 */
		public function getView()
		{
			return $this->view;
		}
		
		/**
		 * @return Controller
		 */
		public function importSettings($settings)
		{
			return $this;
		}
		
		public function getRenderedModel(HttpRequest $request)
		{
			$renderedModel = null;
			
			if($this->hasCacheTicket())
			{
				$this->getCacheTicket()->restoreData();
				
				if($this->getCacheTicket()->isExpired())
				{
					$renderedModel = $this->renderModel($request);
					
					$this->getCacheTicket()->
						setData($renderedModel)->
						storeData();
				}
				else
					$renderedModel = $this->getCacheTicket()->getData();
			}

			if(is_null($renderedModel))
				$renderedModel = $this->renderModel($request);
			
			return $renderedModel;
		}
		
		private function renderModel(HttpRequest $request)
		{
			$view	= $this->getView();
			$model	= $this->getModel($request);
			
			return $view
				? ModelAndView::create()->
					setModel($model)->
					setView($view)->
					render()
				: null;
		}
	}
?>