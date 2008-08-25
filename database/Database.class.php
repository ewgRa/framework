<?php
	/* $Id$ */

	abstract class Database extends Singleton
	{
		private $tables = array();
		
		private $connected = false;
		
		private $host = null;
		private $user = null;
		private $password = null;
		private $databaseName = null;
		private $charset = null;
		
		/**
		 * @return Database
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public static function factory($realization)
		{
			$reflection = new ReflectionMethod($realization, 'create');

			return
				parent::setInstance(__CLASS__, $reflection->invoke(null));
		}
		
		public function setConnected()
		{
			$this->connected = true;
			return $this;
		}
		
		public function __destruct()
		{
			if($this->isConnected())
			{
				$this->disconnect();
			}
		}
		
		public function setHost($host)
		{
			$this->host = $host;
			return $this;
		}
		
		public function getHost()
		{
			return $this->host;
		}
		
		public function setUser($user)
		{
			$this->user = $user;
			return $this;
		}
		
		public function getUser()
		{
			return $this->user;
		}
		
		public function setPassword($passwod)
		{
			$this->password = $passwod;
			return $this;
		}
		
		public function getPassword()
		{
			return $this->password;
		}
				
		public function setCharset($charset = 'utf8')
		{
			$this->charset = $charset;
			return $this;
		}
		
		public function getDatabaseName()
		{
			return $this->databaseName;
		}
		
		public function setDatabaseName($databaseName)
		{
			$this->databaseName = $databaseName;
			return $this;
		}
		
		public function isConnected()
		{
			return $this->connected;
		}

		protected function processQuery($query, $values = array())
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
						{
							$part = "NULL";
						}
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
					{
						$part = "?";
					}
				}
				
				$queryParts[$partKey] = $part;
				$partsCounter++;
			}
			
			return join('', $queryParts);
		}

		public function initialize($yamlFile)
		{
			$settings = YAML::load($yamlFile);

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
		
		public function setTables($tables)
		{
			$this->tables = $tables;
		}
		
		public function queryString($query, $values = array())
		{
			if(count($values))
				$query = $this->processQuery($query, $values);
				
			return $query;
		}
	}
?>