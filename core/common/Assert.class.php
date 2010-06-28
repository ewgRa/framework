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
				throw self::createException($message);
				
			return true;
		}
		
		public static function isTrue(
			$variable,
			$message = 'Variable is not true!'
		) {
			if ($variable !== true)
				throw self::createException($message);
							
			return true;
		}
		
		public static function isFalse(
			$variable,
			$message = 'Variable is not false!'
		) {
			if ($variable !== false)
				throw self::createException($message);
							
			return true;
		}
		
		public static function isNotNull(
			$variable,
			$message = 'Variable is null!'
		) {
			if (is_null($variable))
				throw self::createException($message);
										
			return true;
		}
		
		public static function isNotEmpty(
			$array,
			$message = 'Variable is empty!'
		) {
			if (empty($array))
				throw self::createException($message);
				
			return true;
		}
		
		public static function isEqual(
			$one,
			$two,
			$message = 'one and two not equal'
		) {
			if ($one !== $two)
				throw self::createException($message);
			
			return true;
		}
		
		public static function isImplement(
			$object,
			$interface,
			$message = 'object has not implement interface'
		) {
			if (!in_array($interface, class_implements(get_class($object))))
				throw self::createException($message);
												
			return true;
		}

		public static function isFileExists($filePath)
		{
			if (!file_exists($filePath))
				throw FileNotExistsException::create();
			
			return true;
		}

		public static function isUnreachable()
		{
			throw UnreachableCodeReachedException::create();
		}
		
		protected static function createException($message)
		{
			return WrongArgumentException::create($message);
		}
	}
?>