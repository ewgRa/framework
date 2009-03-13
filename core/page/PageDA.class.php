<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageDA extends DatabaseRequester
	{
		/**
		 * @return PageDA
		 */
		public static function create()
		{
			return new self;
		}
		
		public function getRights($pageId)
		{
			$dbQuery = '
				SELECT
					t1.right_id, t2.path as redirect_page, t3.alias as right_alias
				FROM ' . $this->db()->getTable('PagesRights_ref') . ' t1
				LEFT JOIN ' . $this->db()->getTable('Pages') . ' t2
					ON( t1.redirect_page_id = t2.id )
				LEFT JOIN ' . $this->db()->getTable('Rights') . ' t3
					ON( t3.id = t1.right_id )
				WHERE t1.page_id = ?';

			$dbResult = $this->db()->query($dbQuery, array($pageId));

			return $this->db()->resourceToArray($dbResult);
		}
		
		public function getPage($pageId)
		{
			$dbQuery = "
				SELECT
					t1.*, t2.file_id as layout_file_id
				FROM " . $this->db()->getTable('Pages') . " t1
				LEFT JOIN " . $this->db()->getTable('Layouts') . " t2
					ON( t2.id =	t1.layout_id)
				WHERE t1.id = ?
			";

			$dbResult = $this->db()->query(
				$dbQuery,
				array($pageId)
			);
			
			return $this->db()->fetchArray($dbResult);
		}
	}
?>