<?php
	/* $Id$ */

	class DatabaseException extends DefaultException
	{
		const CONNECT = 1001;
		const SELECT_DATABASE = 1002;
		const SQL_QUERY_ERROR = 1003;
		const UNDEFINED_TABLE = 1004;
		const NO_RESULT = 1005;
		
		private $host = null;
		private $databaseName = null;
		private $tableAlias = null;
		private $query = null;
		private $error = null;
		
		public function setHost($host)
		{
			$this->host = $host;
			return $this;
		}
		
		public function setTableAlias($alias)
		{
			$this->tableAlias = $alias;
			return $this;
		}
		
		public function setDatabaseName($databaseName)
		{
			$this->databaseName = $databaseName;
			return $this;
		}
		
		public function setQuery($query)
		{
			$this->query = $query;
			return $this;
		}
		
		public function setError($error)
		{
			$this->error = $error;
			return $this;
		}
		
		public function __toString()
		{
			$resultString = parent::__toString();
			
			switch( $this->code )
			{
				case self::CONNECT:

					if(!$this->message)
					{
						$this->setMessage('Could not connect to database');
					}
					
					$resultString =
						__CLASS__
						. ": [{$this->code}]:\n\n{$this->message}\n\n"
						. "Host: {$this->host}\n\n";
				break;
				case self::SELECT_DATABASE :

					if(!$this->message)
						$this->setMessage('Could not select database');
					
					$resultString =
						__CLASS__
						. ": [{$this->code}]:\n\n{$this->message}\n\n"
						. "Host: {$this->host}\n\n"
						. "Database: {$this->databaseName}\n\n";
				break;
				case self::SQL_QUERY_ERROR:

					if(!$this->message)
						$this->setMessage('SQL query has error');

					$trace = $this->getSingleTrace(1);

					$resultString =
						__CLASS__
						. ": [{$this->code}]:\n\n{$this->message}\n\n"
						. "Host: {$this->host}\n\n"
						. "Database: {$this->databaseName}\n\n"
						. "Query: {$this->query}\n\n"
						. "Error: {$this->error}\n\n"
						. "Query executed from: {$trace->getFile()} at line {$trace->getLine()}\n\n";
				break;
				case self::UNDEFINED_TABLE:

					if(!$this->message)
						$this->setMessage('Known nothing about DB table alias');
					
					$trace = $this->getSingleTrace(1);
						
					$resultString =
						__CLASS__
						. ": [{$this->code}]:\n\n{$this->message}\n\n"
						. "Table alias: {$this->tableAlias}\n\n"
						. "Get table from: {$trace->getFile()} at line {$trace->getLine()}\n\n";
				break;
			}
			
			return $resultString;
		}
	}
?>