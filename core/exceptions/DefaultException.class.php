<?php
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
		public function setLine($line)
		{
			$this->line = $line;
			return $this;
		}

		/**
		 * @return DefaultException
		 */
		public function setFile($file)
		{
			$this->file = $file;
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
		
		public function toHtmlString()
		{
			return '<pre>' . $this->__toString() . '</pre>';
		}
		
		/**
		 * @return SingleTrace
		 */
		protected function getSingleTrace($index)
		{
			$trace = $this->getTrace();
			$singleRawTrace = $trace[$index];
			
			$singleTrace =
				SingleTrace::create()->
				setLine($singleRawTrace['line'])->
				setFile($singleRawTrace['file']);
			
			
			return $singleTrace;
		}
		
		protected function toString(array $array)
		{
			return join(PHP_EOL.PHP_EOL, $array).PHP_EOL.PHP_EOL;
		}
	}
?>