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
		 */
		public function connect()
		{
			$db = @mysql_connect(
				$this->getHost(),
				$this->getUser(),
				$this->getPassword(),
				true
			);
			
			if(!$db)
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
			
			if(
				!mysql_select_db(
					$this->getDatabaseName(),
					$this->getLinkIdentifier()
				)
			) {
				throw DatabaseException::selectDatabase()->setPool($this);
			}
			
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
			
			if($this->getError())
				$this->queryError();

			$endTime = microtime(true);
				
			if(Singleton::hasInstance('Debug') && Debug::me()->isEnabled())
				$this->debugQuery($query, $startTime, $endTime);
			
			return
				MysqlDatabaseResult::create()->
					setResource($resource);
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

		public function getInsertedId()
		{
			return mysql_insert_id($this->getLinkIdentifier());
		}

		public function escape($variable)
		{
			if(is_array($variable))
			{
				foreach($variable as &$value)
					$value = $this->{__FUNCTION__}($value);
			}
			else
				$variable = mysql_escape_string($variable);
			
			return $variable;
		}
		
		public function getError()
		{
			return mysql_error($this->getLinkIdentifier());
		}
	}
?>