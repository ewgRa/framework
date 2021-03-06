<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface DatabaseInterface
	{
		public static function create();

		public function begin();
		public function commit();
		public function rollback();
		public function quietRollback();

		public function connect();

		public function disconnect();

		/**
		 * @return DatabaseDialectInterface
		 */
		public function getDialect();

		/**
		 * @throws DatabaseQueryException
		 */
		public function query(DatabaseQueryInterface $query);

		/**
		 * @throws DatabaseQueryException
		 */
		public function insertQuery(DatabaseInsertQueryInterface $query);

		/**
		 * @throws DatabaseQueryException
		 */
		public function queryNull(DatabaseQueryInterface $query);

		/**
		 * @throws DatabaseQueryException
		 */
		public function queryRaw($queryString);

		/**
		 * @throws DatabaseQueryException
		 */
		public function queryRawNull($queryString);

		public function getError();
	}
?>