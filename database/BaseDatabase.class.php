<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseDatabase implements DatabaseInterface
	{
		private $linkIdentifier	= null;
		private $tables 		= array();
		private $connected		= false;
		private $host			= null;
		private $user			= null;
		private $password		= null;
		private $databaseName	= null;
		private $charset		= null;
		private $lastQuery		= null;
		
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
		public function setDatabaseName($databaseName)
		{
			$this->databaseName = $databaseName;
			return $this;
		}
		
		public function getDatabaseName()
		{
			return $this->databaseName;
		}
		
		public function getLastQuery()
		{
			return $this->lastQuery;
		}
		
		public function isConnected()
		{
			return $this->connected;
		}

		/**
		 * @return BaseDatabase
		 */
		public function initialize($yamlFile)
		{
			$settings = Yaml::load($yamlFile);

			if(isset($settings['host']))
				$this->setHost($settings['host']);
			
			if(isset($settings['user']))
				$this->setUser($settings['user']);

			if(isset($settings['password']))
				$this->setPassword($settings['password']);

			if(isset($settings['database']))
				$this->setDatabaseName($settings['database']);

			if(isset($settings['charset']))
				$this->setCharset($settings['charset']);

			if(isset($settings['tableAliases']))
				$this->setTables($settings['tableAliases']);
				
			return $this;
		}
		
		public function getTable($alias)
		{
			$result = null;
			
			if(isset($this->tables[$alias]))
				$result = $this->tables[$alias];
			else
				throw
					DatabaseException::create(DatabaseException::UNDEFINED_TABLE)->
					setTableAlias($alias);
				
			return $result;
		}
		
		/**
		 * @return BaseDatabase
		 */
		public function setTables(array $tables)
		{
			$this->tables = $tables;
			return $this;
		}
		
		public function queryString($query, array $values = array())
		{
			if(count($values))
				$query = $this->processQuery($query, $values);
				
			return $query;
		}

		public function __destruct()
		{
			if($this->isConnected())
				$this->disconnect();
		}

		/**
		 * @return BaseDatabase
		 */
		protected function setLastQuery($query)
		{
			$this->lastQuery = $query;
			return $this;
		}
		
		protected function prepareQuery($query, array $values)
		{
			if(!$this->isConnected())
				$this->connect()->selectDatabase()->selectCharset();
			
			if(count($values))
				$query = $this->processQuery($query, $values);
						
			return $query;
		}
		
		protected function processQuery($query, array $values = array())
		{
			$query = str_replace('?', '??', $query);
			$queryParts = explode('?', $query);
			$partsCounter = 0;
			
			foreach($queryParts as $partKey => $part)
			{
				if($partsCounter%2)
				{
					if(!is_null(key($values)))
					{
						$value = $values[key($values)];
						
						if(is_null($value))
							$part = "NULL";
						else
						{
							$value = $this->escape($value);
							
							if(is_array($value))
								$part = "'" . join("', '", $value) . "'";
							else
								$part = "'" . $value . "'";
						}

						next($values);
					}
					else
						$part = "?";
				}
				
				$queryParts[$partKey] = $part;
				$partsCounter++;
			}
			
			return join('', $queryParts);
		}
		
		protected function queryError()
		{
			throw
				DatabaseException::create(DatabaseException::SQL_QUERY_ERROR)->
					setPool($this)->
					setPoolLastQuery($this->getLastQuery())->
					setPoolError($this->getError());
		}
		
		protected function debugQuery($query, $started, $ended)
		{
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
		
		protected function getLinkIdentifier()
		{
			return $this->linkIdentifier;
		}
		
		/**
		 * @return BaseDatabase
		 */
		protected function connected()
		{
			$this->connected = true;
			return $this;
		}
	}
?>