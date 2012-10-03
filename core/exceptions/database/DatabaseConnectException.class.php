<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * FIXME: drop this and use mmore common ConnectException
	*/
	class DatabaseConnectException extends DefaultException
	{
		/**
		 * @return DatabaseConnectException
		 */
		public static function create($message = 'Could not connect to database')
		{
			return new self($message);
		}
	}
?>