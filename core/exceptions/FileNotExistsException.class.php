<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class FileNotExistsException extends DefaultException
	{
		private $filePath = null;
		
		/**
		 * @return FileNotExistsException
		 */
		public static function create($message = 'File doesn\'t exists', $code = 1)
		{
			return new self($message, $code);
		}
				
		/**
		 * @return FileNotExistsException
		 */
		public function setFilePath($filePath)
		{
			$this->filePath = $filePath;
			return $this;
		}
		
		public function __toString()
		{
			$result = array(
				__CLASS__." [{$this->code}]: ".$this->message,
				"File path: {$this->filePath}",
				"Current directory: ".getcwd()
			);
			
			return $this->toString($result);
		}
	}
?>