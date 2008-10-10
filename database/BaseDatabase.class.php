<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	abstract class BaseDatabase implements BaseDatabaseInterface
	{
		private $tables 		= array();
		private $connected		= false;
		private $host			= null;
		private $user			= null;
		private $password		= null;
		private $databaseName	= null;
		private $charset		= null;
		
		/**
		 * @return BaseDatabase
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		/**
		 * @return BaseDatabase
		 */
		public static function factory($realization)
		{
			return
				self::setInstance('Database', new $realization);
		}
		
		/**
		 * @return BaseDatabase
		 */
		public function connected()
		{
			$this->connected = true;
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
		
		public function getDatabaseName()
		{
			return $this->databaseName;
		}
		
		/**
		 * @return BaseDatabase
		 */
		public function setDatabaseName($databaseName)
		{
			$this->databaseName = $databaseName;
			return $this;
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
			
			if(isset($this->tables[$alias])) $result = $this->tables[$alias];
			else
				throw ExceptionsMapper::me()->createException(
					'Database',
					DatabaseException::UNDEFINED_TABLE
				)->
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

		protected function prepareQuery($query, array $values)
		{
			if(!$this->isConnected())
				$this->connect()->selectDatabase()->selectCharset();
			
			if(count($values))
				$query = $this->processQuery($query, $values);
			
			if(Debug::me()->isEnabled())
			{
				$debugItem = DebugItem::create()->
					setType(DebugItem::DATABASE)->
					setData($query)->
					setTrace(debug_backtrace());
				
				Debug::me()->addItem($debugItem);
			}
			
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
		
		public function __destruct()
		{
			if($this->isConnected())
				$this->disconnect();
		}
	}
?>