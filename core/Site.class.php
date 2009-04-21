<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Site
	{
		private $id = null;
		private $alias = null;
		
		/**
		 * @var SiteDA
		 */
		private static $da = null;
		
		/**
		 * @return Site
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return SiteDA
		 */
		public static function da()
		{
			if(!self::$da)
				self::$da = SiteDA::create();

			return self::$da;
		}
		
		/**
		 * @return Site
		 */
		public function setId($id)
		{
			$this->id = $id;
			return $this;
		}
		
		public function getId()
		{
			return $this->id;
		}
		
		/**
		 * @return Site
		 */
		public function setAlias($alias)
		{
			$this->alias = $alias;
			return $this;
		}
		
		public function getAlias()
		{
			return $this->alias;
		}
	}
?>