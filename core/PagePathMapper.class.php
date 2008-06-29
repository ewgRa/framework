<?php
	class PagePathMapper extends Singleton
	{
		const CACHE_LIFE_TIME = 86400;
		
		private $map = null;
		
		/**
		 * @return PageUrlRewriter
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function loadMap()
		{
			$dbQuery = '
				SELECT path, id FROM ' . Database::me()->getTable('Pages') . '
				WHERE preg IS NOT NULL
			';

			$dbResult = Database::me()->query($dbQuery);

			while($dbRow = Database::me()->fetchArray($dbResult))
				$this->map[$dbRow['id']] = $dbRow['path'];
			
			return $this;
		}
		
		public function getPageId($path)
		{
			$result = null;
			
			foreach($this->map as $pageId => $pagePattern)
			{
				if(preg_match('@' . $pagePattern . '@', $path))
				{
					$result = $pageId;
					break;
				}
			}
			
			return $result;
		}
	}
?>