<?php
	/* $Id$ */

	// FIXME: tested?
	class Page extends Singleton
	{
		/**
		 * @return Page
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public static function create($pageId)
		{
			parent::setInstance(
				__CLASS__,
				self::loadPage($pageId)
			);
			
			self::me()->afterLoadPage();
		}
		
		private static function loadPage($pageId)
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
			
			$pageInstance = null;
						
			switch($page['view_type'])
			{
				case View::XSLT:
					$pageInstance = HtmlPage::create();
				break;
			}
			
			if($page['preg'])
				$pageInstance->setPreg();
						
			$pageInstance->
				setId($page['id'])->
				setLayoutFileId(
					Config::me()->replaceVariables($page['layout_file_id'])
				)->
				setViewType($page['view_type'])->
				setPath($page['path'])->
				loadRights();

			return $pageInstance;
		}
	}
?>