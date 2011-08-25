<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class StringUtils
	{
		// FIXME: rename to upperKeyFirst
		public static function upperKeyFirstAlpha($string)
		{
			return
				self::toUpper(mb_substr($string, 0, 1, 'utf8'))
				.self::substr($string, 1);
		}

		// FIXME: rename to lowerKeyFirst
		public static function lowerKeyFirstAlpha($string)
		{
			return
				self::toLower(mb_substr($string, 0, 1, 'utf8'))
				.self::substr($string, 1);
		}

		public static function getLength($string)
		{
			return mb_strlen($string, 'utf8');
		}

		public static function toLower($string)
		{
			return mb_strtolower($string, 'utf8');
		}

		public static function toUpper($string)
		{
			return mb_strtoupper($string, 'utf8');
		}

		public static function substr($string, $start, $length = null)
		{
			return
				$length
					? mb_substr($string, $start, $length, 'utf8')
					: mb_substr($string, $start, self::getLength($string)-$start, 'utf8');
		}

		public static function getClassNamespace($className)
		{
			$nameParts = explode('\\', $className);
			array_pop($nameParts);
			return join('\\', $nameParts);
		}

		public static function getClassName($className)
		{
			$nameParts = explode('\\', $className);
			return array_pop($nameParts);
		}
	}
?>