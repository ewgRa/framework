<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MysqlDatabase extends BaseDatabase
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
		 * @throws DatabaseException::CONNECT
		 */
		public function connect()
		{
			$db =
				@mysql_connect(
					$this->getHost(),
					$this->getUser(),
					$this->getPassword(),
					true
				);
			
			if (!$db)
				throw DatabaseException::connect()->setPool($this);
			
			$this->setLinkIdentifier($db)->connected();

			return $this;
		}
		
		/**
		 * @return MysqlDatabase
		 */
		public function selectCharset($charset = 'utf8')
		{
			$this->setCharset($charset);
			
			$this->
				queryNull('SET NAMES ?', array($charset))->
				queryNull('SET CHARACTER SET ?', array($charset))->
				queryNull(
					'SET collation_connection = ?',
					array($charset . '_general_ci')
				);
			
			return $this;
		}

		/**
		 * @return MysqlDatabase
		 * @throws DatabaseException::SELECT_DATABASE
		 */
		public function selectDatabase($databaseName = null)
		{
			if($databaseName)
				$this->setDatabaseName($databaseName);
			else
				$databaseName = $this->getDatabaseName();
			
			if(
				!mysql_select_db(
					$this->getDatabaseName(),
					$this->getLinkIdentifier()
				)
			)
				throw DatabaseException::selectDatabase()->setPool($this);
			
			return $this;
		}

		/**
		 * @return MysqlDatabase
		 */
		public function disconnect()
		{
			mysql_close($this->getLinkIdentifier());
			$this->connected = false;
			return $this;
		}

		/**
		 * @todo think about $values must be a DBValue::equal, DBValue::like
		 * 		 or something else instance
		 */
		public function query($query, array $values = array())
		{
			$startTime = microtime(true);
			
			$query = $this->prepareQuery($query, $values);

			$resource = mysql_query($query, $this->getLinkIdentifier());
			
			$this->setLastQuery($query);
			
			if ($this->getError())
				$this->queryError();

			$endTime = microtime(true);
				
			if (Singleton::hasInstance('Debug') && Debug::me()->isEnabled())
				$this->debugQuery($query, $startTime, $endTime);
			
			return
				MysqlDatabaseResult::create()->
				setResource($resource);
		}

		public function queryNull($query, array $values = array())
		{
			$this->query($query, $values);
			return $this;
		}
		
		public function getLimit($count = null, $from = null)
		{
			if (!is_null($from) && $from < 0)
				$from = 0;
			
			if (!is_null($count) && $count < 0)
				$count = 0;
			
			$limit = array();
			
			if (!is_null($from))
				$limit[] = (int)$from;
			
			if (!is_null($count))
				$limit[] = (int)$count;
			
			return
				count($limit)
					? ' LIMIT ' . join(', ', $limit)
					: '';
		}

		public function getInsertedId()
		{
			return mysql_insert_id($this->getLinkIdentifier());
		}

		public function escape($variable)
		{
			if (is_array($variable)) {
				foreach ($variable as &$value)
					$value = $this->{__FUNCTION__}($value);
			} else {
				if (!$this->isConnected())
					$this->connect();
				
				$variable =
					mysql_real_escape_string(
						$variable,
						$this->getLinkIdentifier()
					);
			}
			
			return $variable;
		}
		
		public function getError()
		{
			return mysql_error($this->getLinkIdentifier());
		}
	}
?>