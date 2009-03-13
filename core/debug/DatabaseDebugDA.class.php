<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseDebugDA extends DatabaseRequester
	{
		/**
		 * @return DatabaseDebugDA
		 */
		public static function create()
		{
			return new self;
		}
		
		public function insertItem($sessionId, $data)
		{
			$dbQuery = "INSERT INTO " . $this->db()->getTable('DebugData')
				. " SET session_id = ?, data = ?";
			
			$this->db()->query(
				$dbQuery,
				array($sessionId, $data)
			);
			
			return $this->db()->getInsertedId();
		}
	}
?>