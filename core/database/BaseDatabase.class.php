<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseDatabase implements DatabaseInterface
	{
		private $linkIdentifier	= null;
		private $connected		= false;
		private $host			= null;
		private $user			= null;
		private $password		= null;
		private $database		= null;
		private $charset		= null;
		
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

		protected function debugQuery($query, $started, $ended)
		{
			if (!Singleton::hasInstance('Debug') || !Debug::me()->isEnabled())
				return $this;
			
			$debugItem =
				DebugItem::create()->
				setType(DebugItem::DATABASE)->
				setData($query)->
				setTrace(debug_backtrace())->
				setStartTime($started)->
				setEndTime($ended);
			
			Debug::me()->addItem($debugItem);
			
			return $this;
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