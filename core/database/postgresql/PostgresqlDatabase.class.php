<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PostgresqlDatabase extends BaseDatabase
	{
		private $dialect = null;

		/**
		 * @return PostgresqlDatabase
		 */
		public static function create()
		{
			return new self;
		}

		public function __construct()
		{
			$this->dialect = PostgresqlDialect::me();
		}

		/**
		 * @return PostgresqlDialect
		 */
		public function getDialect()
		{
			return $this->dialect;
		}

		public function begin()
		{
			$this->queryRawNull('begin');
			return $this;
		}

		public function commit()
		{
			$this->queryRawNull('commit');
			return $this;
		}

		public function rollback()
		{
			$this->queryRawNull('rollback');
			return $this;
		}

		/**
		 * @return PostgresqlDatabase
		 * @throws DatabaseConnectException
		 */
		public function connect()
		{
			if (!function_exists('pg_connect'))
				throw DefaultException::create('Postgresql extension not installed');

			$db =
				@pg_connect(
					join(
						' ',
						array(
							'host='.$this->getHost(),
							'user='.$this->getUser(),
							'password='.$this->getPassword(),
							'dbname='.$this->getDatabase()
						)
					),
					PGSQL_CONNECT_FORCE_NEW
				);

			if (!$db)
				throw DatabaseConnectException::create();

			$this->
				setLinkIdentifier($db)->
				connected()->
				selectCharset();

			return $this;
		}

		/**
		 * @return PostgresqlDatabase
		 */
		public function selectCharset($charset = null)
		{
			if ($charset)
				$this->setCharset($charset);
			else
				$charset = $this->getCharset();

			pg_setclientencoding(
				$this->getLinkIdentifier(),
				$charset
			);

			return $this;
		}

		/**
		 * @return PostgresqlDatabase
		 */
		public function disconnect()
		{
			pg_close($this->getLinkIdentifier());

			return parent::disconnect();
		}

		public function getError()
		{
			return pg_errormessage($this->getLinkIdentifier());
		}

		public function insertQuery(DatabaseInsertQueryInterface $query)
		{
			$result =
				$this->insertQueryRaw(
					$query->toString($this->getDialect(), $this)
					.' RETURNING '.$this->getDialect()->escapeField(
						$query->getPrimaryField()
					)
				);

			$row = $result->fetchRow();

			$result->setInsertedId($row[$query->getPrimaryField()]);

			return $result;
		}

		protected function runQuery($queryString)
		{
			$result =
				@pg_query(
					$this->getLinkIdentifier(),
					$queryString
				);

			if (pg_result_error($result))
				throw DatabaseQueryException::create();

			return $result;
		}

		protected function createResult()
		{
			return PostgresqlDatabaseResult::create();
		}

		protected function createInsertResult()
		{
			return PostgresqlDatabaseInsertResult::create();
		}
	}
?>