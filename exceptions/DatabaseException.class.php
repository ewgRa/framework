<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseException extends DefaultException
	{
		const CONNECT			= 1001;
		const SELECT_DATABASE	= 1002;
		const SQL_QUERY_ERROR	= 1003;
		const UNDEFINED_TABLE	= 1004;
		const NO_RESULT			= 1005;
		
		private $pool			= null;
		private $poolError		= null;
		private $poolLastQuery	= null;
		private $tableAlias		= null;
		
		/**
		 * @return DatabaseException
		 */
		public static function create($code = null, $message = null)
		{
			return new self($message, $code);
		}
				
		/**
		 * @return DatabaseException
		 */
		public function setPool(BaseDatabase $pool)
		{
			$this->pool = $pool;
			return $this;
		}
		
		/**
		 * @return BaseDatabase
		 */
		public function getPool()
		{
			return $this->pool;
		}
		
		/**
		 * @return DatabaseException
		 */
		public function setPoolLastQuery($query)
		{
			$this->poolLastQuery = $query;
			return $this;
		}
		
		/**
		 * @return DatabaseException
		 */
		public function setPoolError($error)
		{
			$this->poolError = $error;
			return $this;
		}
		
		/**
		 * @return DatabaseException
		 */
		public function setTableAlias($alias)
		{
			$this->tableAlias = $alias;
			return $this;
		}

		public function __toString()
		{
			$resultString = array(parent::__toString());
			
			switch($this->code)
			{
				case self::CONNECT:
					if(!$this->message)
						$this->setMessage('Could not connect to database');
					
					$resultString = array(
						__CLASS__ . ": [{$this->code}]:",
						$this->message,
						"Host: {$this->getPool()->getHost()}"
					);
				break;

				case self::SELECT_DATABASE :
					if(!$this->message)
						$this->setMessage('Could not select database');
					
					$resultString = array(
						__CLASS__ . ": [{$this->code}]:",
						$this->message,
						"Host: {$this->getPool()->getHost()}",
						"Database: {$this->getPool()->getDatabaseName()}"
					);
				break;

				case self::SQL_QUERY_ERROR:
					if(!$this->message)
						$this->setMessage('SQL query has error');

					$trace = $this->getSingleTrace(2);

					$resultString = array(
						__CLASS__ . ": [{$this->code}]:",
						$this->message,
						"Host: {$this->getPool()->getHost()}",
						"Database: {$this->getPool()->getDatabaseName()}",
						"Query: {$this->poolLastQuery}",
						"Error: {$this->poolError}",
						"Query executed from: {$trace->getFile()}"
							. " at line {$trace->getLine()}"
					);
				break;

				case self::UNDEFINED_TABLE:
					if(!$this->message)
						$this->setMessage('Known nothing about DB table alias');
					
					$trace = $this->getSingleTrace(2);
						
					$resultString = array(
						__CLASS__ . ": [{$this->code}]:",
						$this->message,
						"Table alias: {$this->tableAlias}",
						"Get table from: {$trace->getFile()}"
							. " at line {$trace->getLine()}"
					);
				break;
			}
			
			$resultString[] = '';
			
			return join(PHP_EOL . PHP_EOL, $resultString);
		}
	}
?>