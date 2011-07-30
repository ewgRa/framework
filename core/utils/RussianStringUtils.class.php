<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class RussianStringUtils
	{
		private static $alphabet = array(
			'а' => 'А', 'б' => 'Б', 'в' => 'В', 'г' => 'Г', 'д' => 'Д', 'е' => 'Е',
			'ё' => 'Ё', 'ж' => 'Ж', 'з' => 'З', 'и' => 'И', 'й' => 'Й', 'к' => 'К',
			'л' => 'Л', 'м' => 'М', 'н' => 'Н', 'о' => 'О', 'п' => 'П', 'р' => 'Р',
			'с' => 'С', 'т' => 'Т', 'у' => 'У', 'ф' => 'Ф', 'х' => 'Х', 'ц' => 'Ц',
			'ч' => 'Ч', 'ш' => 'Ш', 'щ' => 'Щ', 'ъ' => 'Ъ', 'ы' => 'Ы', 'ь' => 'Ь',
			'э' => 'Э', 'ю' => 'Ю', 'я' => 'Я'
		);

		/*
		 * @link http://ru.wikipedia.org/wiki/ISO_9
		 * @link http://protect.gost.ru/document.aspx?control=7&id=130715
		 */
		private static $translitMap = array(
			'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' =>'e',
			'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k',
			'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
			'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
			'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shh', 'ъ' => '``', 'ы' => 'y`', 'ь' => '`',
			'э' => 'e`', 'ю' => 'yu', 'я' => 'ya',
			'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' =>'E',
			'Ё' => 'Yo', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K',
			'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R',
			'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
			'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Shh', 'Ъ' => '``', 'Ы' => 'Y`', 'Ь' => '`',
			'Э' => 'E`', 'Ю' => 'Yu', 'Я' => 'Ya'
		);

		public static function getAlphabet()
		{
			return self::$alphabet;
		}

		/*
		 * @example selectCaseForNumber($count, array('слово', 'слова', 'слов'))
		 */
		public static function selectCaseForNumber($number, $cases)
		{
			if (($number % 10) == 1 && ($number % 100) != 11) {
				return $cases[0];
			} else if (
				($number % 10) > 1
				&& ($number % 10) < 5
				&& ($number % 100 < 10 || $number % 100 > 20)
			) {
				return $cases[1];
			}

			return $cases[2];
		}

		public static function translit($string)
		{
			return strtr($string, self::$translitMap);
		}

		public static function urlTranslit($string)
		{
			$string = self::translit($string);

			$string = preg_replace('/\./', '_', $string);
			$string = preg_replace( '/[^\w^\s]/', '', $string);
			$string = preg_replace('/\s+/', '_', $string);
			$string = preg_replace('/_+/', '_', $string);

			return StringUtils::toLower($string);
		}
	}
?>