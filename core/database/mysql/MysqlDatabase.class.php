<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MysqlDatabase extends BaseDatabase
	{
		/**
		 * @return MysqlDatabase
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return MysqlDialect
		 */
		public function getDialect()
		{
			return MysqlDialect::me();
		}

		/**
		 * @return MysqlDatabase
		 * @throws DatabaseConnectException
		 */
		public function connect()
		{
			$db =
				@mysql_connect(
					$this->getHost(),
					$this->getUser(),
					$this->getPassword(),
					true
				);

			if (!$db)
				throw DatabaseConnectException::create();

			$this->
				setLinkIdentifier($db)->
				connected()->
				selectDatabase()->
				selectCharset();

			return $this;
		}

		/**
		 * @return MysqlDatabase
		 */
		public function selectCharset($charset = null)
		{
			if ($charset)
				$this->setCharset($charset);
			else
				$charset = $this->getCharset();

			$this->
				queryRawNull('SET NAMES '.$this->getDialect()->escape($charset, $this))->
				queryRawNull(
					'SET CHARACTER SET '.$this->getDialect()->escape($charset, $this)
				)->
				queryRawNull(
					'SET collation_connection = '
					.$this->getDialect()->escape($charset.'_general_ci', $this)
				);

			return $this;
		}

		/**
		 * @return MysqlDatabase
		 * @throws DatabaseSelectDatabaseException
		 */
		public function selectDatabase($databaseName = null)
		{
			if($databaseName)
				$this->setDatabaseName($databaseName);
			else
				$databaseName = $this->getDatabase();

			if(
				!mysql_select_db(
					$this->getDatabase(),
					$this->getLinkIdentifier()
				)
			)
				throw DatabaseSelectDatabaseException::create();

			return $this;
		}

		/**
		 * @return MysqlDatabase
		 */
		public function disconnect()
		{
			mysql_close($this->getLinkIdentifier());
			$this->disconnected();
			return $this;
		}

		public function getInsertedId()
		{
			return mysql_insert_id($this->getLinkIdentifier());
		}

		public function getError()
		{
			return mysql_error($this->getLinkIdentifier());
		}

		protected function runQuery($queryString)
		{
			return mysql_query(
				$queryString,
				$this->getLinkIdentifier()
			);
		}

		protected function createResult()
		{
			return MysqlDatabaseResult::create();
		}
	}
?>