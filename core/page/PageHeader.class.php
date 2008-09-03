<?php
	/* $Id$ */

	// FIXME: tested?
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
		
		public function add($alias, $value = null)
		{
			$this->headers[$alias] = $value;
			return $this;
		}
		
		public function output()
		{
			foreach($this->getHeaders() as $alias => $value)
				header($alias . ($value ? $value : ''));
			
			return $this;
		}
	}
?>