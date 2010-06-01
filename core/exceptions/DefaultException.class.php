<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @codeCoverageIgnoreStart
	*/
	class DefaultException extends Exception
	{
		/**
		 * @return DefaultException
		 */
		public static function create($message = null, $code = null)
		{
			return new self($message, $code);
		}
		
		public function toHtmlString()
		{
			return '<pre>'.$this->__toString().'</pre>';
		}
	}
?>