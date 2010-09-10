<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Debug extends Singleton
	{
		private $hash = null;
		
		private $enabled = null;
		private $items	 = array();
		
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
		
		/**
		 * @return Debug
		 */
		public function addItem(DebugItemInterface $item)
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

		/**
		 *@return CmsDebugItem
		 */
		public function createRequestDebugItem()
		{
			return
				RequestDebugItem::create()->
				setData(
					array(
						'get'	 	=> $_GET,
						'post'	 	=> $_POST,
						'server' 	=> $_SERVER,
						'cookie' 	=> $_COOKIE,
						'session'	=>
							isset($_SESSION)
								? $_SESSION
								: array()
					)
				);
		}
		
		public function getHash()
		{
			if (!$this->hash)
				$this->hash = md5(microtime().' '.rand(0, 1000000));
			
			return $this->hash;
		}
	}
?>