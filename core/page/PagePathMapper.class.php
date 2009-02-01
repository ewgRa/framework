<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * // FIXME: tested?
	*/
	final class PagePathMapper
	{
		const NON_PREG	= 0;
		const PREG		= 1;

		/**
		 * @var PagePathMapperDA
		 */
		private $da = null;
		
		private $map = null;

		/**
		 * @return PagePathMapper
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return PagePathMapperDA
		 */
		public function da()
		{
			if(!$this->da)
				$this->da = PagePathMapperDA::create();
			
			return $this->da;
		}

		/**
		 * @return PagePathMapper
		 */
		public function loadMap()
		{
			$this->map = array(
				self::NON_PREG => array(),
				self::PREG => array()
			);
			
			foreach($this->da()->getMap() as $map)
			{
				$preg = is_null($map['preg']) ? self::NON_PREG : self::PREG;
				$this->map[$preg][$map['id']] = $map['path'];
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
				foreach($this->map[self::PREG] as $pageId => $pagePattern)
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