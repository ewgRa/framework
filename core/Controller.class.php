<?php
	/* $Id$ */

	abstract class Controller
	{
		private $sectionId = null;
		private $positionInSection = null;
		private $cacheTicket = null;
		private $viewFileId = null;
		
		public function setSectionId($sectionId)
		{
			$this->sectionId = $sectionId;
			return $this;
		}

		public function getSectionId()
		{
			return $this->sectionId;
		}
		
		public function setPositionInSection($position)
		{
			$this->positionInSection = $position;
			return $this;
		}

		public function getPositionInSection()
		{
			return $this->positionInSection;
		}
		
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
		
		public function setViewFileId($fileId)
		{
			$this->viewFileId = $fileId;
			return $this;
		}
		
		public function getViewFileId()
		{
			return $this->viewFileId;
		}
		
		public function createView()
		{
			return
				$this->getViewFileId() ?
					ViewFactory::createByFileId($this->getViewFileId())
					: null;
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
			return
				ModelAndView::create()->
					setModel($this->getModel())->
					setView($this->createView())->
					render();
		}
	}
?>