<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
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
		
		public function getLayoutIncludeFiles($fileId)
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
			
			return $dbResult->fetchList();
		}
		
		public function getFile($fileId)
		{
			$dbQuery = '
				SELECT * FROM ' . $this->db()->getTable('ViewFiles') . '
				WHERE id = ?
			';
			
			$dbResult = $this->db()->query($dbQuery, array($fileId));

			return $dbResult->recordCount()
				? $dbResult->fetchArray()
				: null;
		}
	}
?>