<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	class DatabaseDebug extends BaseDebug
	{
		/**
		 * @return DatabaseDebug
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return DatabaseDebug
		 */
		public function store()
		{
			$dbQuery = "INSERT INTO " . Database::me()->getTable('DebugData')
				. " SET session_id = ?, data = ?";


			foreach($this->getItems() as $item)
				$item->dropTrace();
			
			Database::me()->query(
				$dbQuery,
				array(Session::me()->getId(), serialize($this->getItems()))
			);
			
			return $this;
		}
	}
?>