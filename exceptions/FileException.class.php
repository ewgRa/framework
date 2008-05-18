<?php
	class FileException extends DefaultException
	{
		const FILE_NOT_EXISTS = 2001;
		
		private $filePath = null;
		
		public function setFilePath($filePath)
		{
			$this->filePath = $filePath;
			return $this;
		}
		
		public function __toString()
		{
			$resultString = parent::__toString();
			
			switch( $this->code )
			{
				case self::FILE_NOT_EXISTS:

					if(!$this->message)
					{
						$this->setMessage('File doesn\'t exists');	
					}
					
					$resultString = 
						__CLASS__
						. ": [{$this->code}]:\n\n{$this->message}\n\n"
						. "Filepath: {$this->filePath}\n\n"
						. "Current directory: " . getcwd() . "\n\n";
				break;
			}
			
			return $resultString;
		}
	}
?>