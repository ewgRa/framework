<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * // FIXME: tested?
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