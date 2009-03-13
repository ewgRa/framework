<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * // FIXME: tested?
	*/
	final class ControllerDispatcherDA extends DatabaseRequester
	{
		/**
		 * @return ControllerDispatcherDA
		 */
		public static function create()
		{
			return new self;
		}
		
		public function getPageControllers($pageId)
		{
			$dbQuery = '
				SELECT
					t1.*, t2.section_id, t2.position_in_section, t2.controller_settings,
					t2.view_file_id
				FROM ' . $this->db()->getTable('Controllers') . ' t1
				INNER JOIN ' . $this->db()->getTable('PagesControllers_ref') . ' t2
					ON( t1.id = t2.controller_id AND t2.page_id = ? )
				ORDER BY load_priority, load_priority IS NULL
			';
			
			$dbResult = $this->db()->query($dbQuery, array($pageId));

			return $this->db()->resourceToArray($dbResult);
		}
	}
?>