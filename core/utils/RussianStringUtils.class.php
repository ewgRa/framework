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
			'ч' => 'Ч', 'ш' => 'Ш', 'щ' => 'Щ', 'ь' => 'Ь', 'ъ' => 'Ъ', 'ы' => 'Ы',
			'э' => 'Э', 'ю' => 'Ю', 'я' => 'Я'
		);

		public static function getAlphabet()
		{
			return self::$alphabet;
		}
	}
?>