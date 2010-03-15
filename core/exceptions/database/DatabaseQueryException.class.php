<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseQueryException extends BaseDatabaseException
	{
		private $poolError		= null;
		private $poolLastQuery	= null;
		
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
		public function setPoolLastQuery($query)
		{
			$this->poolLastQuery = $query;
			return $this;
		}
		
		/**
		 * @return DatabaseQueryException
		 */
		public function setPoolError($error)
		{
			$this->poolError = $error;
			return $this;
		}
		
		public function __toString()
		{
			$singleTrace = $this->getSingleTrace(3);

			$result = array(
				__CLASS__.": [{$this->code}]:",
				$this->message,
				"Host: {$this->getPool()->getHost()}",
				"Database: {$this->getPool()->getDatabaseName()}",
				"Query: {$this->poolLastQuery}",
				"Error: {$this->poolError}",
				"Query executed from: {$singleTrace->getFile()}"
				. " at line {$singleTrace->getLine()}"
			);
					
			return join(PHP_EOL.PHP_EOL, $result).PHP_EOL.PHP_EOL;
		}
	}
?>