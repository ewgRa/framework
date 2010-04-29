<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseCache implements CacheInterface
	{
		private $namespace	= null;
		private $enabled	= true;

		/**
		 * @return BaseCache
		 */
		public function enable()
		{
			$this->enabled = true;
			return $this;
		}
		
		/**
		 * @return BaseCache
		 */
		public function disable()
		{
			$this->enabled = false;
			return $this;
		}
				
		public function isDisabled()
		{
			return !$this->enabled;
		}
		
		/**
		 * @return BaseCache
		 */
		public function setNamespace($namespace)
		{
			$this->namespace = $namespace;
			return $this;
		}
		
		public function getNamespace()
		{
			return $this->namespace;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function createTicket()
		{
			return
				CacheTicket::create()->
				setCacheInstance($this);
		}
		
		/**
		 * @return BaseCache
		 */
		public function drop(CacheTicket $cacheTicket)
		{
			$this->dropByKey($this->compileKey($cacheTicket));
			return $this;
		}
		
		/**
		 * @return BaseCache
		 */
		protected function debug(CacheTicket $ticket)
		{
			$debugItem =
				DebugItem::create()->
				setType(DebugItem::CACHE)->
				setData(clone $ticket);
			
			Debug::me()->addItem($debugItem);
			
			return $this;
		}
	}
?>