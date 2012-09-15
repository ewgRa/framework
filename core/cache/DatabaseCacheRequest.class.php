<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseCacheRequest
	{
		private $query 	= null;
		private $dbPool 	= null;
		private $cachePool 	= null;

		/**
		 * @return DatabaseCacheRequest
		 */
		public static function create(
			DatabaseQueryInterface $dbQuery,
			$dbPool,
			$cachePool
		) {
			return new self($dbQuery, $dbPool, $cachePool);
		}

		/**
		 * @return DatabaseCacheRequest
		 */
		public function __construct(
			DatabaseQueryInterface $dbQuery,
			$dbPool,
			$cachePool
		) {
			$this->query 		= $dbQuery;
			$this->dbPool 		= $dbPool;
			$this->cachePool 	= $cachePool;
		}

		public function getDbPool()
		{
			return $this->dbPool;
		}

		/**
		 * @return DatabaseInterface
		 */
		public function getDb()
		{
			return Database::me()->getPool($this->dbPool);
		}

		/**
		 * @return CacheInterface
		 */
		public function getCache()
		{
			return Cache::me()->getPool($this->cachePool);
		}

		/**
		 * @return DatabaseQueryInterface
		 */
		public function getQuery()
		{
			return $this->query;
		}
	}
?>