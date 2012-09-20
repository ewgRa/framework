<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DefaultDatabaseCacheWorker
	{
		/**
		 * @var DatabaseQueryInterface
		 */
		private $database	= null;

		/**
		 * @var CacheInterface
		 */
		private $cache		= null;

		/**
		 * @return DefaultDatabaseCacheWorker
		 */
		public static function create(
			DatabaseInterface $database,
			CacheInterface $cache
		) {
			return new self($database, $cache);
		}

		public function __construct(
			DatabaseInterface $database,
			CacheInterface $cache
		) {
			$this->database = $database;
			$this->cache = $cache;
		}

		public function getCached(
			DatabaseQueryInterface $query,
			array $tags
		) {
			$cacheTicket = $this->createTicket();

			$tagsVersionList = $this->getTagsVersionList($tags);

			$result =
				$cacheTicket->
				setKey(__FUNCTION__, $tags, $query)->
				restoreData();

			if (
				!$cacheTicket->isExpired()
				&& $tagsVersionList != $result['tags']
			) {
				$cacheTicket->drop();
			}

			if ($cacheTicket->isExpired()) {
				$dbResult = $this->database->query($query);

				if ($dbResult->recordCount()) {
					\ewgraFramework\Assert::isEqual(
						$dbResult->recordCount(),
						1,
						'query returned more than one row'
					);
				}

				$result = array(
					'tags' => $tagsVersionList,
					'data' => $dbResult->fetchRow()
				);

				$cacheTicket->storeData($result);
			}

			return $result['data'];
		}

		public function getCachedList(
			DatabaseQueryInterface $query,
			array $tags
		) {
			$cacheTicket = $this->createTicket();

			$tagsVersionList = $this->getTagsVersionList($tags);

			$result =
				$cacheTicket->
				setKey(__FUNCTION__, $tags, $query)->
				restoreData();

			if (
				!$cacheTicket->isExpired()
				&& $tagsVersionList != $result['tags']
			) {
				$cacheTicket->drop();
			}

			if ($cacheTicket->isExpired()) {
				$dbResult = $this->database->query($query);

				$result = array(
					'tags' => $tagsVersionList,
					'data' => $dbResult->fetchList()
				);

				$cacheTicket->storeData($result);
			}

			return $result['data'];
		}

		/**
		 * @return DefaultCacheWorker
		 */
		public function dropCache(
			DatabaseQueryInterface $query,
			array $tags
		) {
			$this->cache->multiDrop(
				$this->createTagsTicketList($tags)
			);

			return $this;
		}

		/**
		 * @return DefaultCacheWorker
		 */
		private function getTagsVersionList(array $tags) {
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
		private function createTicket()
		{
			return $this->cache->createTicket();
		}

		/**
		 * @return CacheTicket
		 */
		private function createTagTicket($tag)
		{
			$result = $this->createTicket();
			$result->setPrefix($tag.'-tag');
			$result->setKey($tag);

			return $result;
		}

		/**
		 * @return array
		 */
		private function createTagsTicketList(array $tags) {
			$result = array();

			foreach ($tags as $tag)
				$result[$tag] = $this->createTagTicket($tag);

			return $result;
		}
	}
?>