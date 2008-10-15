<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * // FIXME: tested?
	*/
	class Page extends Singleton
	{
		private $id				= null;
		private $layoutFileId	= null;
		private $preg			= null;
		private $rights			= null;
		private $path			= null;
		
		/**
		 * @return Page
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		/**
		 * @return Page
		 */
		public function setId($id)
		{
			$this->id = $id;
			return $this;
		}

		public function getId()
		{
			return $this->id;
		}

		/**
		 * @return Page
		 */
		public function setLayoutFileId($fileId)
		{
			$this->layoutFileId = $fileId;
			return $this;
		}

		public function getLayoutFileId()
		{
			return $this->layoutFileId;
		}

		/**
		 * @return Page
		 */
		public function setPath($path)
		{
			$this->path = $path;
			return $this;
		}

		public function getPath()
		{
			return $this->path;
		}
		
		/**
		 * @return Page
		 */
		public function setPreg()
		{
			$this->preg = true;
			return $this;
		}

		public function isPreg()
		{
			return $this->preg == true;
		}
		
		/**
		 * @return Page
		 */
		public function setRights(array $rights)
		{
			$this->rights = $rights;
			return $this;
		}

		public function getRights()
		{
			return $this->rights;
		}

		/**
		 * @return Page
		 */
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
		
		/**
		 * @return Page
		 */
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

			return $this;
		}

		/**
		 * @return PageHeader
		 */
		public function getHeader()
		{
			return PageHeader::me();
		}
		
		/**
		 * @return Page
		 */
		public function load($pageId)
		{
			$dbQuery = "
				SELECT
					t1.*, t2.file_id as layout_file_id
				FROM " . Database::me()->getTable('Pages') . " t1
				LEFT JOIN " . Database::me()->getTable('Layouts') . " t2
					ON( t2.id =	t1.layout_id)
				WHERE t1.id = ?
			";

			$dbResult = Database::me()->query(
				$dbQuery,
				array($pageId)
			);
			
			$page = Database::me()->fetchArray($dbResult);
			
			if($page['preg'])
				$this->setPreg();
						
			$this->
				setId($page['id'])->
				setLayoutFileId($page['layout_file_id'])->
				setPath(
					Config::me()->replaceVariables($page['path'])
				)->
				loadRights();

			return $this;
		}
	}
?>