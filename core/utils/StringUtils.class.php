<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class StringUtils
	{
		public static $replacement = array(
			'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' =>'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'i', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'р' => 'r', 'п' => 'p', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'ch', 'щ' => 'sch', 'ь' => '', 'ъ' => '', 'э' => 'e', 'ю' => 'ju', 'я' => 'ja', 'ы' => 'y',
			'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' =>'E', 'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'I', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'Ts', 'Ч' => 'Ch', 'Ш' => 'Ch', 'Щ' => 'ScH', 'Ь' => '', 'Ъ' => '', 'Э' => 'E', 'Ю' => 'Ju', 'Я' => 'Ja', 'Ы' => 'Y'
		);

		public static function translit($string)
		{
			return strtr($string, self::$replacement);
		}

		public static function URLTranslit($string)
		{
			$string = self::translit($string);
			$string = preg_replace('/\./', '_', $string);
			$string = preg_replace( '/[^\w^\s]/', '', $string);
			$string = preg_replace('/\s+/', '_', $string);
			$string = preg_replace('/_+/', '_', $string);
			return mb_convert_case($string, MB_CASE_LOWER);
		}

		public static function upperKeyFirstAlpha($string)
		{
			return ucfirst($string);
		}

		public static function separateByUpperKey($string)
		{
			$string = preg_replace('/([A-Z])/', "_$1", $string);

			return mb_strtolower($string);
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