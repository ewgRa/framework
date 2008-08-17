<?php
	/* $Id$ */
	
	class Assert
	{
		public static function notNull($variable, $message)
		{
			if(is_null($variable))
			{
				throw new Exception($message);
			}
		}
		
		public static function fileExists($filePath)
		{
			if(!file_exists($filePath))
			{
				$exception = new FileException(null, FileException::FILE_NOT_EXISTS);
				$exception->setFilePath($filePath);

				$trace = array_shift(debug_backtrace());

				throw
					ExceptionsMapper::me()->createException(
						'File',
						FileException::FILE_NOT_EXISTS
					)->
					setFilePath($filePath)->
					setFile($trace['file'])->
					setLine($trace['line']);
			}
		}
	}
?>