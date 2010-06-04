<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class FileNotExistsException extends DefaultException
	{
		/**
		 * @return FileNotExistsException
		 */
		public static function create($message = 'File doesn\'t exists')
		{
			return new self($message);
		}
	}
?>