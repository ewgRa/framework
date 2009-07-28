<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
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
		
		/**
		 * @return DefaultException
		 */
		public function setCode($code)
		{
			$this->code = $code;
			return $this;
		}

		/**
		 * @return DefaultException
		 */
		public function setMessage($message)
		{
			$this->message = $message;
			return $this;
		}
		
		/**
		 * @return Trace
		 */
		protected function getSingleTrace($index)
		{
			$trace = $this->getTrace();
			$singleTrace = $trace[$index];
			
			$trace = Trace::create()->
				setLine($singleTrace['line'])->
				setFile($singleTrace['file']);
			
			
			return $trace;
		}
		
		public function toHtmlString()
		{
			return '<pre>' . $this->__toString() .'</pre>';
		}
	}
?>