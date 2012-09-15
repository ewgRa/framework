<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DefaultDatabaseCacheWorker extends Singleton
	{
		/**
		 * @return DefaultDatabaseCacheWorker
		 * method needed for methods hinting
		 */
		public static function me()
		{
			return parent::me();
		}

		public function getCached(
			DatabaseCacheRequest $request,
			array $tags
		) {
			$cacheTicket = $this->createTicket($request);

			$tagsVersionList = $this->getTagsVersionList($request, $tags);

			$result =
				$cacheTicket->
				setKey(__FUNCTION__, $tags, $request->getQuery())->
				restoreData();

			if (
				!$cacheTicket->isExpired()
				&& $tagsVersionList != $result['tags']
			) {
				$cacheTicket->drop();
			}

			if ($cacheTicket->isExpired()) {
				$dbInstance = $request->getDb();
				$dbResult = $dbInstance->query($request->getQuery());

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
			DatabaseCacheRequest $request,
			array $tags
		) {
			$cacheTicket = $this->createTicket($request);

			$tagsVersionList = $this->getTagsVersionList($request, $tags);

			$result =
				$cacheTicket->
				setKey(__FUNCTION__, $tags, $request->getQuery())->
				restoreData();

			if (
				!$cacheTicket->isExpired()
				&& $tagsVersionList != $result['tags']
			) {
				$cacheTicket->drop();
			}

			if ($cacheTicket->isExpired()) {
				$dbInstance = $request->getDb();
				$dbResult = $dbInstance->query($request->getQuery());

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
			DatabaseCacheRequest $request,
			array $tags
		) {
			$request->getCache()->multiDrop(
				$this->createTagsTicketList($request, $tags)
			);

			return $this;
		}

		/**
		 * @return DefaultCacheWorker
		 */
		private function getTagsVersionList(
			DatabaseCacheRequest $request,
			array $tags
		) {
			$cacheInstance = $request->getCache();

			$tagsTicketList = $this->createTagsTicketList($request, $tags);

			$cacheResult = $cacheInstance->multiGet($tagsTicketList);

			$dataToStore = array();
			$ticketsToStore = array();

			foreach ($tagsTicketList as $tag => $tagTicket) {
				if ($tagTicket->isExpired()) {
					$cacheResult[$tag] = array('version' => microtime(true));

					$dataToStore[$tag] = $cacheResult[$tag];
					$ticketsToStore[$tag] = $tagTicket;
				}
			}

			$cacheInstance->multiSet($ticketsToStore, $dataToStore);

			return $cacheResult;
		}

		/**
		 * @return CacheTicket
		 */
		private function createTicket(DatabaseCacheRequest $request) {
			return
				$request->
					getCache()->
					createTicket()->
					setPrefix($request->getDbPool().'-database');
		}

		/**
		 * @return CacheTicket
		 */
		private function createTagTicket(
			DatabaseCacheRequest $request,
			$tag
		) {
			$result = $this->createTicket($request);
			$result->setPrefix($result->getPrefix().'-'.$tag.'-tag');
			$result->setKey($tag);

			return $result;
		}

		/**
		 * @return array
		 */
		private function createTagsTicketList(
			DatabaseCacheRequest $request,
			array $tags
		) {
			$result = array();

			foreach ($tags as $tag)
				$result[$tag] = $this->createTagTicket($request, $tag);

			return $result;
		}
	}
?>