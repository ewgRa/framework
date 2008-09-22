<?php
	/* $Id$ */

	class DefaultException extends Exception
	{
		public function setCode($code)
		{
			$this->code = $code;
			return $this;
		}

		public function setMessage($message)
		{
			$this->message = $message;
			return $this;
		}
		
		/**
		 * @param integer $index
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