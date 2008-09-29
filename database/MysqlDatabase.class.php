<?php
	/* $Id$ */

	class MysqlDatabase extends Database
	{
		/**
		 * @return MysqlDatabase
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return MysqlDatabase
		 */
		public function connect()
		{
			$db = mysql_connect($this->getHost(), $this->getUser(), $this->getPassword());
			
			if(!$db)
			{
				throw
					ExceptionsMapper::me()->createException('Database')->
						setCode(DatabaseException::CONNECT)->
						setHost($this->getHost());
			}
			
			$this->setConnected();
			return $this;
		}
		
		/**
		 * @return MysqlDatabase
		 */
		public function selectCharset($charset = 'utf8')
		{
			$this->setCharset($charset);
			
			$this->query('SET NAMES ?', array($charset));
			$this->query('SET CHARACTER SET ?', array($charset));
			
			$this->query(
				'SET collation_connection = ?',
				array($charset . '_general_ci')
			);
			
			return $this;
		}

		/**
		 * @return MysqlDatabase
		 */
		public function selectDatabase($databaseName = null)
		{
			if($databaseName)
				$this->setDatabaseName($databaseName);
			else
				$databaseName = $this->getDatabaseName();
			
			if(!mysql_select_db($this->getDatabaseName()))
			{
				throw
					ExceptionsMapper::me()->createException('Database')->
						setCode(DatabaseException::SELECT_DATABASE)->
						setHost($this->getHost())->
						setDatabaseName($this->getDatabaseName());
			}
			
			return $this;
		}

		/**
		 * @return MysqlDatabase
		 */
		public function disconnect()
		{
			mysql_close();
			$this->connected = false;
			return $this;
		}

		/**
		 * @todo think about $values must be a DBValue::equal, DBValue::like
		 * 		 or something else instance
		 */
		public function query($query, $values = array())
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
				
			$resource = mysql_query($query);
			
			if(mysql_error())
			{
				throw
					ExceptionsMapper::me()->createException('Database')->
						setCode(DatabaseException::SQL_QUERY_ERROR)->
						setHost($this->getHost())->
						setDatabaseName($this->getDatabaseName())->
						setQuery($query)->
						setError(mysql_error());
			}

			return $resource;
		}

		public function recordCount($resource)
		{
			return mysql_numrows($resource);
		}

		public function fetchArray($resource)
		{
			return mysql_fetch_assoc($resource);
		}

		/**
		 * @return MysqlDatabase
		 */
		public function dataSeek($resource, $row)
		{
			mysql_data_seek($resource, $row - 1);
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

		protected function escape($variable)
		{
			if(is_array($variable))
			{
				foreach($variable as &$value)
					$value = $this->escape($value);
			}
			else
				$variable = mysql_escape_string($variable);
			
			return $variable;
		}
	}
?>