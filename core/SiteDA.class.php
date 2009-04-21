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
		
		public function getSiteByAlias($alias)
		{
			$result = null;
			
			$dbQuery = '
				SELECT id
				FROM ' . $this->db()->getTable('Site') . '
				WHERE alias = ?
			';

			$dbResult = $this->db()->query($dbQuery, array($alias));

			if($this->db()->recordCount($dbResult))
				$result = $this->db()->fetchArray($dbResult);
			else
				throw ExceptionsMapper::me()->createException('NotFound');
			
			return
				Site::create()->
					setAlias($alias)->
					setId($result['id']);
		}
	}
?>