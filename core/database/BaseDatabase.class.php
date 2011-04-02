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

		public function query(DatabaseQueryInterface $query)
		{
			return $this->queryRaw($query->toString($this->getDialect(), $this));
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
			if (!$this->isConnected())
				$this->connect()->selectDatabase()->selectCharset();

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

			return $this->createResult()->setResource($resource);
		}

		public function isConnected()
		{
			return $this->connected;
		}

		public function getLinkIdentifier()
		{
			return $this->linkIdentifier;
		}

		public function __destruct()
		{
			if ($this->isConnected())
				$this->disconnect();
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