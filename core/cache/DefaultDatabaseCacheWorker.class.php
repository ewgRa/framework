<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DefaultDatabaseCacheWorker extends DefaultCacheWorker
	{
		/**
		 * @var DatabaseQueryInterface
		 */
		private $database	= null;

		/**
		 * @return DefaultDatabaseCacheWorker
		 */
		public static function create(
			CacheInterface $cache,
			DatabaseInterface $database = null
		) {
			return new self($cache, $database);
		}

		public function __construct(
			CacheInterface $cache,
			DatabaseInterface $database
		) {
			$this->database = $database;

			parent::__construct($cache);
		}

		public function getCached(
			DatabaseQueryInterface $query,
			array $tags,
			\Closure $resultCallback = null
		) {
			$cacheTicket = $this->createTicket();
			$cacheTicket->setKey(__FUNCTION__, $tags, $query);

			$result = $cacheTicket->restoreData();

			$tagsVersionList = $this->getTagsVersionList($tags);

			if (
				!$cacheTicket->isExpired()
				&& $tagsVersionList != $result['tags']
			) {
				$cacheTicket->drop();
			}

			if ($cacheTicket->isExpired()) {
				$result = array('tags' => $tagsVersionList);

				if ($resultCallback)
					$result['data'] = $resultCallback();
				else {
					$dbResult = $this->database->query($query);

					if ($dbResult->recordCount()) {
						\ewgraFramework\Assert::isEqual(
							$dbResult->recordCount(),
							1,
							'query returned more than one row'
						);
					}

					$result['data'] = $dbResult->fetchRow();
				}

				$cacheTicket->storeData($result);
			}

			return $result['data'];
		}

		public function getCachedList(
			DatabaseQueryInterface $query,
			array $tags,
			\Closure $resultCallback = null
		) {
			$cacheTicket = $this->createTicket();
			$cacheTicket->setKey(__FUNCTION__, $tags, $query);

			$result = $cacheTicket->restoreData();

			$tagsVersionList = $this->getTagsVersionList($tags);

			if (
				!$cacheTicket->isExpired()
				&& $tagsVersionList != $result['tags']
			) {
				$cacheTicket->drop();
			}

			if ($cacheTicket->isExpired()) {
				$result = array('tags' => $tagsVersionList);

				if ($resultCallback)
					$result['data'] = $resultCallback();
				else {
					$dbResult = $this->database->query($query);
					$result['data'] = $dbResult->fetchList();
				}

				$cacheTicket->storeData($result);
			}

			return $result['data'];
		}
	}
?>