<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class EnglishStringUtils
	{
		private static $alphabet = array(
			'a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D', 'e' => 'E', 'f' => 'F',
			'g' => 'G', 'h' => 'H', 'i' => 'I', 'j' => 'J', 'k' => 'K', 'l' => 'L',
			'm' => 'M', 'n' => 'N', 'o' => 'O', 'p' => 'P', 'q' => 'Q', 'r' => 'R',
			's' => 'S', 't' => 'T', 'u' => 'U', 'v' => 'V', 'w' => 'W', 'x' => 'X',
			'y' => 'Y', 'z' => 'Z'
		);

		public static function getAlphabet()
		{
			return self::$alphabet;
		}

		public static function separateByUpperKey($string)
		{
			$string = preg_replace('/([A-Z])/', "_$1", $string);

			return mb_strtolower($string);
		}
	}
?>