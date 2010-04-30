<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseQueryException extends BaseDatabaseException
	{
		private $error		= null;
		private $query	= null;
		
		/**
		 * @return DatabaseQueryException
		 */
		public static function create($message = 'SQL query has error', $code = 1)
		{
			return new self($message, $code);
		}

		/**
		 * @return DatabaseQueryException
		 */
		public function setQuery($query)
		{
			$this->query = $query;
			return $this;
		}
		
		/**
		 * @return DatabaseQueryException
		 */
		public function setError($error)
		{
			$this->error = $error;
			return $this;
		}
		
		public function __toString()
		{
			$singleTrace = $this->getSingleTrace(3);

			$result = array(
				__CLASS__." [{$this->code}]: ".$this->message,
				"Host: {$this->getPool()->getHost()}",
				"Database: {$this->getPool()->getDatabaseName()}",
				"Query: {$this->query}",
				"Error: {$this->error}",
				"Query executed from: {$singleTrace->getFile()}"
				. " at line {$singleTrace->getLine()}"
			);
					
			return $this->toString($result);
		}
	}
?>