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
		public static function create($message = 'File doesn\'t exists', $code = 1)
		{
			return new self($message, $code);
		}
	}
?>