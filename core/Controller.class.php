<?php
	/* $Id$ */

	abstract class Controller
	{
		private $sectionId = null;
		private $positionInSection = null;
		private $cacheKey = null;
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
		
		public function hasCacheKey()
		{
			return !is_null($this->cacheKey);
		}
		
		public function getCacheKey()
		{
			return $this->cacheKey;
		}
		
		protected function setCacheKey()
		{
			$this->cacheKey = func_get_args();
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
				View::createByFileId($this->getViewFileId());
		}
		
		abstract public function getModel();


		public function getRenderedModel()
		{
			return
				ModelAndView::create()->
					setModel($this->getModel())->
					setView($this->createView())->
					render();
		}
	}
?>