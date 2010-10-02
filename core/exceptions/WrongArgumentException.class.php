<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class WrongArgumentException extends DefaultException
	{
		/**
		 * @return WrongArgumentException
		 */
		public static function create($message = 'Wrorg argument')
		{
			return new self($message);
		}
	}
?>