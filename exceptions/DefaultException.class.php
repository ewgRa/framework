<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	class DefaultException extends Exception
	{
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