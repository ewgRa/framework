<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseDatabase extends Observable implements DatabaseInterface
	{
		const QUERY_EVENT = 1;

		private $linkIdentifier	= null;
		private $connected		= false;
		private $host			= null;
		private $user			= null;
		private $password		= null;
		private $database		= null;
		private $charset		= null;

		abstract protected function runQuery($queryString);

		abstract protected function createResult();

		abstract protected function createInsertResult();

		public function quietRollback()
		{
			try {
				$this->rollback();
			} catch(\Exception $e) {
				// quiet
			}

			return $this;
		}

		/**
		 * @return BaseDatabase
		 */
		public function setHost($host)
		{
			$this->host = $host;
			return $this;
		}

		public function getHost()
		{
			return $this->host;
		}

		/**
		 * @return BaseDatabase
		 */
		public function setUser($user)
		{
			$this->user = $user;
			return $this;
		}

		public function getUser()
		{
			return $this->user;
		}

		/**
		 * @return BaseDatabase
		 */
		public function setPassword($passwod)
		{
			$this->password = $passwod;
			return $this;
		}

		public function getPassword()
		{
			return $this->password;
		}

		/**
		 * @return BaseDatabase
		 */
		public function setCharset($charset = 'utf8')
		{
			$this->charset = $charset;
			return $this;
		}

		public function getCharset()
		{
			return $this->charset;
		}

		/**
		 * @return BaseDatabase
		 */
		public function setDatabase($database)
		{
			$this->database = $database;
			return $this;
		}

		public function getDatabase()
		{
			return $this->database;
		}

		public function insertQuery(DatabaseInsertQueryInterface $query)
		{
			return
				$this->insertQueryRaw(
					$query->toString($this->getDialect(), $this)
				);
		}

		public function query(DatabaseQueryInterface $query)
		{
			return
				$this->queryRaw(
					$query->toString($this->getDialect(), $this)
				);
		}

		public function queryNull(DatabaseQueryInterface $query)
		{
			$this->query($query);
			return $this;
		}

		public function queryRawNull($queryString)
		{
			$this->queryRaw($queryString);
			return $this;
		}

		public function queryRaw($queryString)
		{
			return
				$this->
					createResult()->
					setResource($this->queryRawResource($queryString));
		}

		public function queryRawResource($queryString)
		{
			if (!$this->isConnected())
				$this->connect();

			$startTime = microtime(true);

			Assert::isNotNull($this->getLinkIdentifier());

			$resource = $this->runQuery($queryString);

			if ($error = $this->getError())
				throw DatabaseQueryException::create($error);

			$this->notifyObservers(
				self::QUERY_EVENT,
				Model::create()->
				set('query', $queryString)->
				set('startTime', $startTime)->
				set('endTime', microtime(true))
			);

			return $resource;
		}

		public function isConnected()
		{
			return $this->connected;
		}

		public function getLinkIdentifier()
		{
			return $this->linkIdentifier;
		}

		public function disconnect()
		{
			$this->linkIdentifier = null;
			$this->disconnected();
			return $this;
		}

		public function __destruct()
		{
			if ($this->isConnected()) {
				$this->disconnect();
			}
		}

		public function __sleep()
		{
			throw UnsupportedException::create();
		}

		/**
		 * @return DatabaseInsertResultInterface
		 */
		protected function insertQueryRaw($queryString)
		{
			return
				$this->
					createInsertResult()->
					setResource($this->queryRawResource($queryString));
		}

		/**
		 * @return BaseDatabase
		 */
		protected function setLinkIdentifier($link)
		{
			$this->linkIdentifier = $link;
			return $this;
		}

		/**
		 * @return BaseDatabase
		 */
		protected function connected()
		{
			$this->connected = true;
			return $this;
		}

		/**
		 * @return BaseDatabase
		 */
		protected function disconnected()
		{
			$this->connected = false;
			return $this;
		}
	}
?>