<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseQueryException extends DefaultException
	{
		/**
		 * @return DatabaseQueryException
		 */
		public static function create($message = 'SQL query has error')
		{
			return new self($message);
		}
	}
?>