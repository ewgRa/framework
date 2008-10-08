<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * // FIXME: tested?
	*/
	class PageHeader extends Singleton
	{
		private $headers = array();
		
		/**
		 * @return Page
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function getHeaders()
		{
			return $this->headers;
		}
		
		/**
		 * @return PageHeader
		 */
		public function add($alias, $value = null)
		{
			$this->headers[$alias] = $value;
			return $this;
		}
		
		/**
		 * @return PageHeader
		 */
		public function output()
		{
			foreach($this->getHeaders() as $alias => $value)
				header($alias . ($value ? $value : ''));
			
			return $this;
		}
	}
?>