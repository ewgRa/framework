<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @codeCoverageIgnoreStart
	*/
	class DefaultException extends \Exception
	{
		/**
		 * @return DefaultException
		 */
		public static function create($message = null)
		{
			return new self($message);
		}

		public function toHtmlString()
		{
			return '<pre>'.$this->__toString().'</pre>';
		}
	}
?>