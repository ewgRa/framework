<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MemcachedBasedCache extends BaseCache
	{
		/**
		 * @var Memcache
		 */
		private $memcache = null;

		/**
		 * @return MemcachedBasedCache
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return MemcachedBasedCache
		 */
		public function addServer($host, $port)
		{
			$this->getMemcache()->addServer($host, $port);

			return $this;
		}

		public function multiGet(array $tickets)
		{
			$keys = array();

			foreach ($tickets as $key => $ticket)
				$keys[$key] = $this->compileKey($ticket);

			$data = $this->getMemcache()->get($keys);
			$result = array();

			foreach ($keys as $key => $ticketKey) {
				$ticket = $tickets[$key];

				if (!isset($data[$ticketKey])) {
					$ticket->setExpiredTime(null);
					$ticket->expired();
				} else if ($data[$ticketKey]['lifeTime'] >= time()) {
					$ticket->
						setExpiredTime($data[$ticketKey]['lifeTime'])->
						actual();

					$result[$key] = $data[$ticketKey]['data'];
				} else {
					$ticket->setExpiredTime(null);
					$ticket->expired();
				}
			}

			return $result;
		}

		public function get(CacheTicket $ticket)
		{
			$result = null;

			$key = $this->compileKey($ticket);

			if ($data = $this->getMemcache()->get($key)) {

				if ($data['lifeTime'] >= time()) {
					$ticket->
						setExpiredTime($data['lifeTime'])->
						actual();

					$result = $data['data'];
				} else {
					$ticket->setExpiredTime(null);
					$ticket->expired();
				}
			} else {
				$ticket->setExpiredTime(null);
				$ticket->expired();
			}

			$this->notifyObservers(
				self::GET_TICKET_EVENT,
				Model::create()->
				set('ticket', $ticket)
			);

			return $result;
		}

		/**
		 * @return MemcachedBasedCache
		 */
		public function set(CacheTicket $ticket, $data)
		{
			$lifeTime = $ticket->getLifeTime();

			if (is_null($lifeTime))
				$lifeTime = Cache::FOREVER;

			$lifeTime += time();

			Assert::isTrue($lifeTime > time());

			$key = $this->compileKey($ticket);

			$data = array(
				'data' 		=> $data,
				'lifeTime' 	=> $lifeTime
			);

			$ticket->setExpiredTime($lifeTime);

			$this->getMemcache()->set($key, $data, 0, $lifeTime);

			$ticket->actual();

			return $this;
		}

		/**
		 * @return MemcachedBasedCache
		 */
		public function dropByKey($key)
		{
			# NOTE: zero needed for back compability with memcached protocol
			$this->getMemcache()->delete($key, 0);
			return $this;
		}

		/**
		 * @return MemcachedBasedCache
		 */
		public function clean()
		{
			$this->getMemcache()->flush();
			return $this;
		}

		/**
		 * @return Memcache
		 */
		private function getMemcache()
		{
			if (!$this->memcache)
				$this->memcache = new \Memcache();

			return $this->memcache;
		}
	}
?>