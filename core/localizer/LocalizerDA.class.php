<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
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