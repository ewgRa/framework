<?php
	/* $Id$ */
	
	class Debug extends Singleton
	{
		private $deliverRealization = null;
		private $enabled = null;
		private $items = array();
		
		/**
		 * @return Debug
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		/**
		 * @return Debug
		 */
		public function enable()
		{
			$this->enabled = true;
			return $this;
		}
		
		/**
		 * @return Debug
		 */
		public function disable()
		{
			$this->enabled = null;
			return $this;
		}
		
		public function isEnabled()
		{
			return $this->enabled;
		}
		
		public function addItem(DebugItem $item)
		{
			$this->items[] = $item;
			return $this;
		}
		
		public function getItems()
		{
			return $this->items;
		}
	}
?>