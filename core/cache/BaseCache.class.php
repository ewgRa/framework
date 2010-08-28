<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseCache extends Observable implements CacheInterface
	{
		const GET_TICKET_EVENT = 1;
		
		private $namespace = null;

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
		
		public function compileKey(CacheTicket $ticket)
		{
			return
				$this->getNamespace().'-'.$ticket->getPrefix()
				.'-'.md5(serialize($ticket->getKey()));
		}
	}
?>