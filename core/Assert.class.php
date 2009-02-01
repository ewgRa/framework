<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	final class Assert
	{
		public static function isArray($array, $message = 'Variable is not array!')
		{
			if(!is_array($array))
				throw ExceptionsMapper::me()->createException('DefaultException')->
					setMessage($message);
				
			return true;
		}
		
		public static function isTrue($variable, $message = 'Variable is not true!')
		{
			if($variable !== true)
				throw ExceptionsMapper::me()->createException('DefaultException')->
					setMessage($message);
							
			return true;
		}
		
		public static function notNull($variable, $message = 'Variable is null!')
		{
			if(is_null($variable))
				throw ExceptionsMapper::me()->createException('DefaultException')->
					setMessage($message);
							
			return true;
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
			
			return true;
		}
	}
?>