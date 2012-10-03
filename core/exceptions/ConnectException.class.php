<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class ConnectException extends DefaultException
	{
		/**
		 * @return ConnectException
		 */
		public static function create($message = 'Could not connect')
		{
			return new self($message);
		}
	}
?>