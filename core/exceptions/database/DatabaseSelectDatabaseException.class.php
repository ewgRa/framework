<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseSelectDatabaseException extends BaseDatabaseException
	{
		/**
		 * @return DatabaseSelectDatabaseException
		 */
		public static function create(
			$message = 'Could not select database',
			$code = 1
		) {
			return new self($message, $code);
		}
		
		public function __toString()
		{
			$result = array(
				__CLASS__." [{$this->code}]: ".$this->message,
				"Host: {$this->getPool()->getHost()}",
				"Database: {$this->getPool()->getDatabaseName()}"
			);
			
			return $this->toString($result);
		}
	}
?>