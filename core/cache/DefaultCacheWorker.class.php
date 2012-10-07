<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class DefaultCacheWorker
	{
		/**
		 * @var CacheInterface
		 */
		private $cache		= null;

		/**
		 * @return DefaultCacheWorker
		 */
		public static function createFromTicket(CacheTicket $ticket)
		{
			return new self($ticket->getCacheInstance());
		}

		/**
		 * @return DefaultCacheWorker
		 */
		public static function create(CacheInterface $cache)
		{
			return new self($cache);
		}

		public function __construct(CacheInterface $cache)
		{
			$this->cache = $cache;
		}

		public function restoreTicketData(CacheTicket $cacheTicket) {
			$result = $cacheTicket->restoreData();

			if (!$cacheTicket->isExpired()) {
				$tagsVersionList =
					$this->getTagsVersionList(
						array_keys($result['tags'])
					);

				if ($tagsVersionList != $result['tags'])
					$cacheTicket->drop();
			}

			return
				$cacheTicket->isExpired()
					? null
					: $result['data'];
		}

		public function storeTicketData(
			\ewgraFramework\CacheTicket $cacheTicket,
			$data,
			$tags
		) {
			$tagsVersionList = $this->getTagsVersionList($tags);

			$storeData = array(
				'tags' => $tagsVersionList,
				'data' => $data
			);

			return $cacheTicket->storeData($storeData);
		}

		/**
		 * @return DefaultCacheWorker
		 */
		public function dropCache(array $tags) {
			$this->cache->multiDrop(
				$this->createTagsTicketList($tags)
			);

			return $this;
		}

		/**
		 * @return DefaultCacheWorker
		 */
		protected function getTagsVersionList(array $tags) {
			$tagsTicketList = $this->createTagsTicketList($tags);

			$cacheResult = $this->cache->multiGet($tagsTicketList);

			$dataToStore = array();
			$ticketsToStore = array();

			foreach ($tagsTicketList as $tag => $tagTicket) {
				if ($tagTicket->isExpired()) {
					$cacheResult[$tag] = array('version' => microtime(true));

					$dataToStore[$tag] = $cacheResult[$tag];
					$ticketsToStore[$tag] = $tagTicket;
				}
			}

			$this->cache->multiSet($ticketsToStore, $dataToStore);

			return $cacheResult;
		}

		/**
		 * @return CacheTicket
		 */
		protected function createTicket()
		{
			return $this->cache->createTicket();
		}

		/**
		 * @return CacheTicket
		 */
		protected function createTagTicket($tag)
		{
			$result = $this->createTicket();
			$result->setPrefix($tag.'-tag');
			$result->setKey($tag);

			return $result;
		}

		/**
		 * @return array
		 */
		protected function createTagsTicketList(array $tags) {
			$result = array();

			foreach (array_unique($tags) as $tag)
				$result[$tag] = $this->createTagTicket($tag);

			return $result;
		}
	}
?>