<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * // FIXME: tested?
	*/
	class SiteDA extends DatabaseRequester
	{
		/**
		 * @return SiteDA
		 */
		public static function create()
		{
			return new self;
		}
		
		public function getSiteByHost($host)
		{
			$result = null;
			
			$dbQuery = "
				SELECT t1.id FROM " . $this->db()->getTable('Site') . " t1
				INNER JOIN " . $this->db()->getTable('SiteHosts') . " t2
					ON(t2.host = ? AND t2.site_id = t1.id)
				GROUP BY t1.id
			";

			$dbResult = $this->db()->query($dbQuery, array($host));

			if($this->db()->recordCount($dbResult))
				$result = $this->db()->fetchArray($dbResult);
			
			return $result;
		}
	}
?>