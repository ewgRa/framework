<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * // FIXME: tested?
	*/
	final class SiteDA extends DatabaseRequester
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
			
			$dbQuery = '
				SELECT t1.id FROM ' . $this->db()->getTable('Site') . ' t1
				INNER JOIN ' . $this->db()->getTable('SiteHosts') . ' t2
					ON(t2.host = ? AND t2.site_id = t1.id)
				GROUP BY t1.id
			';

			$dbResult = $this->db()->query($dbQuery, array($host));

			if($this->db()->recordCount($dbResult))
				$result = $this->db()->fetchArray($dbResult);
			else
				throw ExceptionsMapper::me()->createException('NotFound');
			
			return $result;
		}
	}
?>