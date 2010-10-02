<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @codeCoverageIgnoreStart
	*/
	final class UnimplementedCodeException extends DefaultException
	{
		/**
		 * @return UnimplementedCodeException
		 */
		public static function create($message = null)
		{
			return new self($message);
		}
	}
?>