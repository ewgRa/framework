<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseConnectException extends DefaultException
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
	}
?>