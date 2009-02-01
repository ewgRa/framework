<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	final class LocalizerDA extends DatabaseRequester
	{
		/**
		 * @return LocalizerDA
		 */
		public static function create()
		{
			return new self;
		}
		
		public function loadLanguages()
		{
			$dbQuery = "SELECT * FROM " . $this->db()->getTable('Languages');
			
			$dbResult = $this->db()->query($dbQuery);
			
			return $this->db()->resourceToArray($dbResult);
		}
	}
?>