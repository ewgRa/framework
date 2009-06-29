<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Assert
	{
		public static function isArray($array, $message = 'Variable is not array!')
		{
			if(!is_array($array))
				throw DefaultException::create()->
					setMessage($message);
				
			return true;
		}
		
		public static function isTrue($variable, $message = 'Variable is not true!')
		{
			if($variable !== true)
				throw DefaultException::create()->
					setMessage($message);
							
			return true;
		}
		
		public static function notNull($variable, $message = 'Variable is null!')
		{
			if(is_null($variable))
				throw DefaultException::create()->
					setMessage($message);
							
			return true;
		}
		
		public static function isEqual($one, $two)
		{
			if($one !== $two)
				throw DefaultException::create()->
					setMessage($message);
							
			return true;
		}
		
		public static function fileExists($filePath)
		{
			if(!file_exists($filePath))
			{
				$trace = array_shift(debug_backtrace());

				throw
					FileException::create(FileException::FILE_NOT_EXISTS)->
					setFilePath($filePath)->
					setFile($trace['file'])->
					setLine($trace['line']);
			}
			
			return true;
		}
	}
?>