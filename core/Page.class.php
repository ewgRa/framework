<?php
	/* $Id$ */
	
	class Page extends Singleton
	{
		const CACHE_LIFE_TIME = 86400;

		private $id = null;
		private $viewType = null;
		private $layoutFile = null;
		private $pathMatches = null;
		private $pathParts = null;
		private $preg = null;
		private $title = null;
		private $description = null;
		private $keywords = null;
		private $rights = null;
		private $requestPath = null;
		private $path = null;
		
		/**
		 * @return Page
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		private function setId($id)
		{
			$this->id = $id;
			return $this;
		}

		public function getId()
		{
			return $this->id;
		}

		private function setViewType($viewType)
		{
			$this->viewType = $viewType;
			return $this;
		}

		public function getViewType()
		{
			return $this->viewType;
		}

		private function setLayoutFile($layoutFile)
		{
			$this->layoutFile = $layoutFile;
			return $this;
		}

		public function getLayoutFile()
		{
			return $this->layoutFile;
		}

		private function setPathMatches($pathMatches)
		{
			$this->pathMatches = $pathMatches;
			return $this;
		}

		public function getPathMatches()
		{
			return $this->pathMatches;
		}

		private function setPathParts($pathParts)
		{
			$this->pathParts = $pathParts;
			return $this;
		}

		public function getPathParts()
		{
			return $this->pathParts;
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

		private function setPath($path)
		{
			$this->path = $path;
			return $this;
		}

		public function getPath()
		{
			return $this->path;
		}
		
		public function setRequestPath($path)
		{
			$this->requestPath = $path;
			return $this;
		}

		public function getRequestPath()
		{
			return $this->requestPath;
		}
		
		private function setPreg()
		{
			$this->preg = true;
			return $this;
		}

		public function isPreg()
		{
			return $this->preg == true;
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

		private function setRights($rights)
		{
			$this->rights = $rights;
			return $this;
		}

		public function getRights()
		{
			return $this->rights;
		}

		public function loadPage($pagePath, $pageId = null)
		{
			$dbQuery = "
				SELECT
					t1.*, t3.id as layout_file_id, t3.path as layout_file,
					t4.title, t4.description, t4.keywords
				FROM " . Database::me()->getTable('Pages') . " t1
				LEFT JOIN " . Database::me()->getTable('Layouts') . " t2
					ON( t2.id =	t1.layout_id)
				LEFT JOIN " . Database::me()->getTable('ViewFiles') . " t3
					ON ( t3.id = t2.file_id )
				LEFT JOIN " . Database::me()->getTable('PagesData') . " t4
					ON( t4.page_id = t1.id AND t4.language_id = ? )
				WHERE IF(?, t1.id = ?, t1.path = ?)
			";

			$dbResult = Database::me()->query(
				$dbQuery,
				array(Localizer::me()->getLanguageId(), $pageId, $pageId, $pagePath)
			);
			
			if(Database::me()->recordCount($dbResult))
				$page = Database::me()->fetchArray($dbResult);
			else
				throw
					ExceptionsMapper::me()->createException('Page')->
						setCode(PageException::PAGE_NOT_FOUND)->
						setUrl($pagePath);
			
			if($page['preg'])
				$this->setPreg();
						
			$this->
				setId($page['id'])->
				setLayoutFile(
					Config::me()->replaceVariables($page['layout_file'])
				)->
				setViewType($page['view_type'])->
				setTitle($page['title'])->
				setDescription($page['description'])->
				setKeywords($page['keywords'])->
				setPath($page['path'])->
				loadRights();

			return $this;
		}

		public function processPath()
		{
			$this->setPathParts(explode("/", $this->getRequestPath()));
			
			if($this->isPreg())
			{
				preg_match("@" . $this->getPath() . "@", $this->getRequestPath(), $pagePathMatches);
				$pagePathMatches = $this->processPathMatches($pagePathMatches);
				$this->setPathMatches($pagePathMatches);
			}
		}
		
		private function processPathMatches($pathMatches)
		{
			$result = $pathMatches;

			$dbQuery = "
				SELECT * FROM " . Database::me()->getTable('PagesUrlMatchesKeys') . "
				WHERE page_id = ?
			";

			$dbResult = Database::me()->query($dbQuery, array($this->getId()));

			while($dbRow = Database::me()->fetchArray($dbResult))
			{
				if(isset($pathMatches[$dbRow['match_position']]))
				{
					$result[$dbRow['key']] = $result[$dbRow['match_position']];
					unset($result[$dbRow['match_position']]);
				}
			}

			return $result;
		}

		protected function loadRights()
		{
			$this->rights = array();

			$dbQuery = '
				SELECT
					t1.right_id, t2.path as redirect_page, t3.alias as right_alias
				FROM ' . Database::me()->getTable('PagesRights_ref') . ' t1
				LEFT JOIN ' . Database::me()->getTable('Pages') . ' t2
					ON( t1.redirect_page_id = t2.id )
				LEFT JOIN ' . Database::me()->getTable('Rights') . ' t3
					ON( t3.id = t1.right_id )
				WHERE t1.page_id = ?';

			$dbResult = Database::me()->query($dbQuery, array($this->getId()));

			while($dbRow = Database::me()->fetchArray($dbResult))
			{
				$this->rights[$dbRow['right_id']] = array(
					'redirectPage' => $dbRow['redirect_page'],
					'rightAlias' => $dbRow['right_alias']
				);
			}

			return $this;
		}
		
		public function checkAccessPage($rights)
		{
			if($this->getRights())
			{
				$intersectRights = array_intersect(
					array_keys($this->getRights()), array_keys($rights)
				);

				if(!count($intersectRights))
				{
					$noRights = array_diff(
						array_keys($this->getRights()), $intersectRights
					);
					
					throw
						ExceptionsMapper::me()->createException('Page')->
							setCode(PageException::NO_RIGHTS_TO_ACCESS)->
							setNoRights($noRights)->
							setPageRights($this->getRights());
					}
			}

			return true;
		}
	}
?>