<?php
	/* $Id$ */

	// FIXME: tested?
	class HtmlPage extends Page
	{
		private $title = null;
		private $description = null;
		private $keywords = null;

		public static function create()
		{
			return new self;
		}
		
		private function setTitle($title)
		{
			$this->title = $title;
			return $this;
		}

		public function getTitle()
		{
			return $this->title;
		}

		private function setDescription($description)
		{
			$this->description = $description;
			return $this;
		}

		public function getDescription()
		{
			return $this->description;
		}

		private function setKeywords($keywords)
		{
			$this->keywords = $keywords;
			return $this;
		}

		public function getKeywords()
		{
			return $this->keywords;
		}
		
		public function afterLoadPage()
		{
			$dbQuery = '
				SELECT * FROM ' . Database::me()->getTable('PagesData') . '
				WHERE page_id = ? AND language_id = ?
			';

			$dbResult = Database::me()->query(
				$dbQuery,
				array($this->getId(), Localizer::me()->getLanguageId())
			);
			
			$dbRow = Database::me()->fetchArray($dbResult);
			
			if(isset($dbRow['title']))
				$this->setTitle($dbRow['title']);
			
			if(isset($dbRow['description']))
				$this->setDescription($dbRow['description']);
				
			if(isset($dbRow['keywords']))
				$this->setKeywords($dbRow['keywords']);
				
			return $this;
		}
	}
?>