<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseDebug implements DebugInterface
	{
		private $enabled = null;
		private $items	 = array();
		
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
		
		/**
		 * @return Debug
		 */
		public function addItem(DebugItem $item)
		{
			$this->items[] = $item;
			return $this;
		}
		
		public function getItems()
		{
			return $this->items;
		}

		/**
		 * @return DebugItem
		 */
		public function getItem($index)
		{
			return $this->items[$index];
		}
	}
?>