<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class UnreachableCodeReachedException extends DefaultException
	{
		/**
		 * @return UnreachableCodeReachedException
		 */
		public static function create($message = null)
		{
			return new self($message);
		}
	}
?>