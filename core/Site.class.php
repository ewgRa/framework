<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	final class Site extends Singleton
	{
		private $id = null;
		private $host = null;
		
		/**
		 * @var SiteDA
		 */
		private $da = null;
		
		/**
		 * @return Site
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
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
		public function setHost($host)
		{
			$this->host= $host;
			return $this;
		}
		
		public function getHost()
		{
			return $this->host;
		}
		
		/**
		 * @return SiteDA
		 */
		public function da()
		{
			if(!$this->da)
				$this->da = SiteDA::create();

			return $this->da;
		}
		
		public function define()
		{
			$site = $this->da()->getSiteByHost($this->getHost());
			
			if($site)
				$this->setId($site['id']);
			
			return $this;
		}
	}
?>