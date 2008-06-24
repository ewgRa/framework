<?php
	// FIXME: tested?
	class PageUrlMapper extends Singleton
	{
		const CACHE_LIFE_TIME = 86400;
		
		/**
		 * @return PageUrlRewriter
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		private $map = null;
		
		public function loadMap()
		{
			$dbQuery = '
				SELECT url, id FROM ' . Database::me()->getTable('Pages') . '
				WHERE preg IS NOT NULL
			';

			$dbResult = Database::me()->query($dbQuery);

			while($dbRow = Database::me()->fetchArray($dbResult))
				$this->map[$dbRow['id']] = $dbRow['url'];
			
			return $this;
		}
		
		public function getPageId($url)
		{
			$result = null;
			
			foreach($this->map as $pageId => $pagePattern)
			{
				if(preg_match('@' . $pagePattern . '@', $url))
				{
					$result = $pageId;
					break;
				}
			}
			
			return $result;
		}
	}
?>