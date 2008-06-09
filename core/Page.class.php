<?php
	// FIXME: tested
	// FIXME: refactoring
	// FIXME: cache
	class Page extends Singleton
	{
		const CACHE_LIFE_TIME = 86400;

		private $id = null;
		private $viewType = null;
		private $layoutFile = null;
		private $urlMatches = null;
		private $pathUrlParts = null;
		private $title = null;
		private $description = null;
		private $keywords = null;
		private $rights = null;

		private static $instance = null;

		/**
		 * @return Page
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__, self::$instance);
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

		private function setUrlMatches($urlMatches)
		{
			$this->urlMatches = $urlMatches;
			return $this;
		}

		public function getUrlMatches()
		{
			return $this->urlMatches;
		}

		private function setPathUrlParts($pathUrlParts)
		{
			$this->pathUrlParts = $pathUrlParts;
			return $this;
		}

		public function getPathUrlParts()
		{
			return $this->pathUrlParts;
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

		private function setRights($rights)
		{
			$this->rights = $rights;
			return $this;
		}

		public function getRights()
		{
			return $this->rights;
		}

		public function definePage($pageUrl)
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
				WHERE IF( t1.preg IS NULL, t1.url = ?, ? REGEXP t1.url)
			";

			$dbResult = Database::me()->query(
				$dbQuery,
				array(Localizer::me()->getLanguageId(), $pageUrl, $pageUrl)
			);

			$pages = null;

			if(Database::me()->recordCount($dbResult))
				$pages = Database::me()->resourcetoArray($dbResult);
			else
				throw
					ExceptionsMapper::me()->createException('Page')->
						setCode(PageException::PAGE_NOT_FOUND)->
						setUrl($pageUrl);

			usort($pages, array($this, 'sortPages'));
			$page = array_shift($pages);

			$this->setId($page['id'])->
				setLayoutFile(
					Config::me()->replaceVariables($page['layout_file'])
				)->
				setViewType($page['view_type'])->
				setTitle($page['title'])->
				setDescription($page['description'])->
				setKeywords($page['keywords'])->
				setPathUrlParts(explode("/", $pageUrl))->
				loadRights();

			preg_match("@" . $page['url'] . "@", $pageUrl, $pageUrlMatches);
			$pageUrlMatches = $this->processPageUrlMatches($pageUrlMatches);
			$this->setUrlMatches($pageUrlMatches);

			return $this;
		}

		private function sortPages( &$pageA, &$pageB )
		{
			preg_match( "@" . $pageA['url'] . "@", $pageA['real_url'], $matchesA );
			preg_match( "@" . $pageB['url'] . "@", $pageB['real_url'], $matchesB );

			if(
				(count($matchesA) < count($matchesB) && !is_null($pageA['preg']))
				|| is_null($pageB['preg'])
			)
			{
				return 1;
			}

			return -1;
		}

		private function processPageUrlMatches($urlMatches)
		{
			$dbQuery = "
				SELECT * FROM " . Database::me()->getTable('PagesUrlMatchesKeys') . "
				WHERE page_id = ?
			";

			$dbResult = Database::me()->query($dbQuery, array($this->getId()));

			while($dbRow = Database::me()->fetchArray($dbResult))
			{
				if(isset($urlMatches[$dbRow['match_position']]))
				{
					$urlMatches[$dbRow['key']] = $urlMatches[$dbRow['match_position']];
					unset($urlMatches[$dbRow['match_position']]);
				}
			}

			return $urlMatches;
		}

		protected function loadRights()
		{
			$this->rights = Cache::me()->get(
				array(__CLASS__, __FUNCTION__, $this->getId()),
				'site/pages'
			);

			if(Cache::me()->isExpired())
			{
				$this->rights = array();

				$dbQuery = '
					SELECT
						t1.right_id, t2.url as redirect_page, t3.alias as right_alias
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
						'redirect_page' => $dbRow['redirect_page'],
						'right_alias' => $dbRow['right_alias']
					);
				}

				Cache::me()->set($this->rights, time() + self::CACHE_LIFE_TIME);
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