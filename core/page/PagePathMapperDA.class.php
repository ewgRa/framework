<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PagePathMapperDA extends DatabaseRequester
	{
		/**
		 * @return PagePathMapperDA
		 */
		public static function create()
		{
			return new self;
		}
		
		public function getMap()
		{
			$dbQuery = '
				SELECT path, id, preg
				FROM ' . $this->db()->getTable('Pages') . '
				WHERE status = \'normal\'
			';

			$dbResult = $this->db()->query($dbQuery);
			
			return $this->db()->resourceToArray($dbResult);
		}
	}
?>