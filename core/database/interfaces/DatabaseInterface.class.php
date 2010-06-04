<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface DatabaseInterface
	{
		public static function create();

		public function connect();
		
		public function disconnect();
		
		public function selectCharset($charset = null);
		
		public function selectDatabase($database = null);
		
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
		public function queryNull(DatabaseQueryInterface $query);

		/**
		 * @throws DatabaseQueryException
		 */
		public function queryRaw($queryString);
		
		/**
		 * @throws DatabaseQueryException
		 */
		public function queryRawNull($queryString);
		
		public function getInsertedId();

		public function getError();
	}
?>