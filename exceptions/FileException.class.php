<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class FileException extends DefaultException
	{
		const FILE_NOT_EXISTS 	= 2001;
		
		private $filePath 		= null;
		
		/**
		 * @return FileException
		 */
		public function setFilePath($filePath)
		{
			$this->filePath = $filePath;
			return $this;
		}
		
		public function __toString()
		{
			$resultString = array(parent::__toString());
			
			switch($this->code)
			{
				case self::FILE_NOT_EXISTS:

					if(!$this->message)
						$this->setMessage('File doesn\'t exists');
					
					$resultString = array(
						__CLASS__ . ": [{$this->code}]:",
						$this->message,
						"Filepath: {$this->filePath}",
						"Current directory: " . getcwd()
					);
				break;
			}
			
			$resultString[] = '';
			
			return join(PHP_EOL . PHP_EOL, $resultString);
		}
	}
?>