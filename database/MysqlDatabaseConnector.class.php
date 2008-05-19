<?php
	class MysqlDatabaseConnector
	{
		private $connected = false;
		
		private $host = null;
		private $user = null;
		private $password = null;
		private $database = null;
		private $charset = null;
		
		public function __destruct()
		{
			if($this->isConnected())
			{
				$this->disconnect();
			}
		}
		
		/**
		 * @return MysqlDatabaseConnector
		 */
		public static function create()
		{
			return new self;
		}
		
		public function connect()
		{
			$db = @mysql_connect($this->host, $this->user, $this->password);
			
			if(!$db)
			{
				throw 
					ExceptionsMapper::me()->createHandler('Database')->
						setCode(DatabaseException::CONNECT)->
						setHost($this->host);
			}
			
			$this->connected = true;
			
			return $this;
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
		
		public function setPassword($passwod)
		{
			$this->password = $passwod;
			return $this;
		}
		
		public function setCharset($charset = 'utf8')
		{
			$this->charset = $charset;			
			return $this;
		}
		
		public function selectCharset($charset = 'utf8')
		{
			$this->setCharset($charset);
			
			$this->query('SET NAMES ?', array($charset));
			$this->query('SET CHARACTER SET ?', array($charset));
			
			$this->query(
				'SET collation_connection = ?',
				array($charset . '_general_ci')
			);
		}

		public function setDatabase($database)
		{
			$this->database = $database;			
			return $this;
		}
		
		public function selectDatabase($database)
		{
			$this->setDatabase($database);
			
			if(!mysql_select_db($this->database))
			{
				throw 
					ExceptionsMapper::me()->createHandler('Database')->
						setCode(DatabaseException::SELECT_DATABASE)->
						setHost($this->host)->
						setDatabase($this->database);
			}
			
			return $this;
		}

		public function disconnect()
		{
			mysql_close();
			$this->connected = false;
			return $this;
		}

		public function isConnected()
		{
			return $this->connected;
		}

		// TODO: think about $values must be a DBValue::equal, DBValue::like or something else instance
		public function query($query, $values = array())
		{
			if(count($values))
			{
				$query = $this->processQuery($query, $values);
			}
			
			$resource = mysql_query($query);
			
			if(mysql_error())
			{
				throw 
					ExceptionsMapper::me()->createHandler('Database')->
						setCode(DatabaseException::SQL_QUERY_ERROR)->
						setHost($this->host)->
						setDatabase($this->database)->
						setQuery($query)->
						setError(mysql_error());
			}

			return $resource;
		}

		public function getRecordCount($resource)
		{
			return mysql_numrows($resource);
		}

		public function fetchArray($resource)
		{
			return mysql_fetch_assoc($resource);
		}

		public function dataSeek($resource, $row)
		{
			$row--;
			mysql_data_seek($resource, $row);	
			return $this;
		}

		public function resourceToArray($resource, $field = null)
		{
			$result = array();
			
			if( $resource && $this->recordCount($resource))
			{
				$this->dataSeek($resource, 1);
				
				$row = $this->fetchArray($resource);
				
				while($row)
				{
					$result[] = is_null($field) ? $row : $row[$field];
					$row = $this->fetchArray($resource);
				}
			}
			
			return $result;
		}
		
		public function getLimit($count = null, $from = null)
		{
			$limit = array();
			
			if($from < 0)
				$from = 0;
			
			if($count < 0)
				$count = 0;
			
			if(!is_null($from))
				$limit[] = (int)$from;
			
			if(!is_null($count)) $limit[] = (int)$count;
			
			return count($limit) ? ' LIMIT ' . join(', ', $limit) : '';
		}

		public function getInsertedID()
		{
			return mysql_insert_id();
		}

		private function escape($variable)
		{
			if(is_array($variable))
			{
				foreach($variable as &$value)
				{
					$value = $this->escape($value);
				}
			}
			else
			{
				$variable = mysql_escape_string($variable);
			}
			
			return $variable;
		}

		private function processQuery($query, $values = array())
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
	}
?>