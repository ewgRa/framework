<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseSelectDatabaseException extends DefaultException
	{
		/**
		 * @return DatabaseSelectDatabaseException
		 */
		public static function create($message = 'Could not select database')
		{
			return new self($message);
		}
	}
?>