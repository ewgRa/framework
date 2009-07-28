<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NotFoundException extends DefaultException
	{
		/**
		 * @return NotFoundException
		 */
		public static function create($message = null, $code = null)
		{
			return new self($message, $code);
		}
	}
?>