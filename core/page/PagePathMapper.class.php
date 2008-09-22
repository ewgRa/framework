<?php
	/* $Id$ */

	// FIXME: tested?
	class PagePathMapper
	{
		const NON_PREG = 0;
		const PREG = 1;
		
		private $map = null;
		
		public static function create()
		{
			return new self;
		}
		
		public function loadMap()
		{
			$this->map = array(self::NON_PREG => array(), self::PREG => array());
			
			$dbQuery = '
				SELECT path, id, preg
				FROM ' . Database::me()->getTable('Pages');

			$dbResult = Database::me()->query($dbQuery);

			while($dbRow = Database::me()->fetchArray($dbResult))
			{
				$preg = is_null($dbRow['preg']) ? self::NON_PREG : self::PREG;
				$this->map[$preg][$dbRow['id']] = $dbRow['path'];
			}
			
			$this->map[self::NON_PREG] = array_flip($this->map[self::NON_PREG]);
			
			return $this;
		}
		
		public function getPageId($path)
		{
			$result = null;
			
			if(isset($this->map[self::NON_PREG][$path]))
				$result = $this->map[self::NON_PREG][$path];
			else
			{
				foreach($this->map[self::NON_PREG] as $pageId => $pagePattern)
				{
					if(preg_match('@' . $pagePattern . '@', $path))
					{
						$result = $pageId;
						break;
					}
				}
			}
			
			return $result;
		}
	}
?>