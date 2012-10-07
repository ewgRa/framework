<?php
	namespace ewgraFramework;

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

		public function multiGet(array $tickets)
		{
			$result = array();

			foreach ($tickets as $key => $ticket) {
				$data = $this->get($ticket);

				if (!$ticket->isExpired())
					$result[$key] = $data;
			}

			return $result;
		}

		public function multiSet(array $tickets, array $data)
		{
			foreach ($tickets as $key => $ticket)
				$this->set($ticket, $data[$key]);

			return $this;
		}

		/**
		 * @return BaseCache
		 */
		public function drop(CacheTicket $cacheTicket)
		{
			$this->dropByKey($this->compileKey($cacheTicket));
			$cacheTicket->expired();
			return $this;
		}

		public function multiDrop(array $tickets)
		{
			foreach ($tickets as $key => $ticket)
				$this->drop($ticket);

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