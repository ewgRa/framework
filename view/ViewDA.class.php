<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * // FIXME: tested?
	*/
	final class ViewDA extends DatabaseRequester
	{
		/**
		 * @return ViewDA
		 */
		public static function create()
		{
			return new self;
		}
		
		public function getLayouIncludeFiles($fileId)
		{
			$dbQuery = '
				SELECT t2.path
				FROM ' . $this->db()->getTable('ViewFilesIncludes') . ' t1
				INNER JOIN ' . $this->db()->getTable('ViewFiles') . ' t2
					ON(t2.id = t1.include_file_id)
				WHERE
					t1.file_id = ? AND
					t2.`content-type` = (
						SELECT `content-type`
						FROM ' . $this->db()->getTable('ViewFiles') . '
						WHERE id = ?
					)
			';
			
			$dbResult = $this->db()->query(
				$dbQuery,
				array($fileId, $fileId)
			);
			
			return $this->db()->resourceToArray($dbResult);
		}
		
		public function getFile($fileId)
		{
			$dbQuery = '
				SELECT * FROM ' . $this->db()->getTable('ViewFiles') . '
				WHERE id = ?
			';
			
			$dbResult = $this->db()->query($dbQuery, array($fileId));

			return $this->db()->recordCount($dbResult)
				? $this->db()->fetchArray($dbResult)
				: null;
		}
	}
?>