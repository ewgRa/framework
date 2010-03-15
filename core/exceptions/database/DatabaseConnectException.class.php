<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseConnectException extends BaseDatabaseException
	{
		/**
		 * @return DatabaseConnectException
		 */
		public static function create(
			$message = 'Could not connect to database',
			$code = 1
		) {
			return new self($message, $code);
		}

		public function __toString()
		{
			$result = array(
				__CLASS__.": [{$this->code}]:",
				$this->message,
				"Host: {$this->getPool()->getHost()}"
			);
					
			return join(PHP_EOL.PHP_EOL, $result).PHP_EOL.PHP_EOL;
		}
	}
?>