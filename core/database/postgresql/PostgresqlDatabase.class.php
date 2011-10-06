<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PostgresqlDatabase extends BaseDatabase
	{
		/**
		 * @return PostgresqlDatabase
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return PostgresqlDialect
		 */
		public function getDialect()
		{
			return PostgresqlDialect::me();
		}

		/**
		 * @return PostgresqlDatabase
		 * @throws DatabaseConnectException
		 */
		public function connect()
		{
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
					)
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

		/**
		 * You can get inserted id by "RETURNING" construction
		 * @link http://www.postgresql.org/docs/current/static/sql-insert.html
		 * or use currval(sequence)
		 */
		public function getInsertedId()
		{
			throw UnsupportedException::create();
		}

		public function getError()
		{
			return pg_errormessage($this->getLinkIdentifier());
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
	}
?>