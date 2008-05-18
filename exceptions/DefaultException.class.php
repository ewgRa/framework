<?php
	class DefaultException extends Exception
	{
		public function setLine($line)
		{
			$this->line = $line;
			return $this;
		}
		
		public function setFile($file)
		{
			$this->file = $file;
			return $this;
		}
		
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
	}
?>