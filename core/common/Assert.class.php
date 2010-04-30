<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Assert
	{
		public static function isArray(
			$array,
			$message = 'Variable is not array!'
		) {
			if (!is_array($array))
				throw WrongArgumentException::create($message);
				
			return true;
		}
		
		public static function isTrue(
			$variable,
			$message = 'Variable is not true!'
		) {
			if ($variable !== true)
				throw WrongArgumentException::create($message);
							
			return true;
		}
		
		public static function isFalse(
			$variable,
			$message = 'Variable is not false!'
		) {
			if ($variable !== false)
				throw WrongArgumentException::create($message);
							
			return true;
		}
		
		public static function isNotNull(
			$variable,
			$message = 'Variable is null!'
		) {
			if (is_null($variable))
				throw WrongArgumentException::create($message);
										
			return true;
		}
		
		public static function isEqual(
			$one,
			$two,
			$message = 'one and two not equal'
		) {
			if ($one !== $two)
				throw WrongArgumentException::create($message);
										
			return true;
		}
		
		public static function isFileExists($filePath)
		{
			if (!file_exists($filePath)) {
				$trace = debug_backtrace();
				$trace = array_shift($trace);

				throw
					FileNotExistsException::create()->
					setFilePath($filePath)->
					setFile($trace['file'])->
					setLine($trace['line']);
			}
			
			return true;
		}

		public static function isImplement(
			$object,
			$interface,
			$message = 'object has not implement interface'
		) {
			if (!in_array($interface, class_implements(get_class($object))))
				throw WrongArgumentException::create($message);
												
			return true;
		}

		public static function isUnreachable()
		{
			throw UnreachableCodeReachedException::create();
		}
	}
?>