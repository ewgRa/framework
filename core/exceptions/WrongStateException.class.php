<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class WrongStateException extends DefaultException
	{
		/**
		 * @return WrongStateException
		 */
		public static function create($message = 'Wrong state', $code = null)
		{
			return new self($message, $code);
		}
	}
?>