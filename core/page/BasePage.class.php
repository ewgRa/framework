<?php
	/* $Id$ */

	// FIXME: tested?
	class BasePage
	{
		const CACHE_LIFE_TIME = 86400;

		private $id = null;
		private $viewType = null;
		private $layoutFileId = null;
		private $pathMatches = null;
		private $pathParts = null;
		private $preg = null;
		private $rights = null;
		private $requestPath = null;
		private $path = null;
		
		public function setId($id)
		{
			$this->id = $id;
			return $this;
		}

		public function getId()
		{
			return $this->id;
		}

		public function setViewType($viewType)
		{
			$this->viewType = $viewType;
			return $this;
		}

		public function getViewType()
		{
			return $this->viewType;
		}

		public function setLayoutFileId($fileId)
		{
			$this->layoutFileId = $fileId;
			return $this;
		}

		public function getLayoutFileId()
		{
			return $this->layoutFileId;
		}

		public function setPathMatches($pathMatches)
		{
			$this->pathMatches = $pathMatches;
			return $this;
		}

		public function getPathMatches()
		{
			return $this->pathMatches;
		}

		public function setPathParts($pathParts)
		{
			$this->pathParts = $pathParts;
			return $this;
		}

		public function getPathParts()
		{
			return $this->pathParts;
		}

		public function setPath($path)
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
		
		public function setPreg()
		{
			$this->preg = true;
			return $this;
		}

		public function isPreg()
		{
			return $this->preg == true;
		}
		
		public function setRights($rights)
		{
			$this->rights = $rights;
			return $this;
		}

		public function getRights()
		{
			return $this->rights;
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
		
		public function processPathMatches($pathMatches)
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

		public function loadRights()
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

		/**
		 * @return PageHeader
		 */
		public function getHeader()
		{
			return PageHeader::me();
		}
	}
?>