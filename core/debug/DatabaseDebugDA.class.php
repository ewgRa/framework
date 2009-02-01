<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
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