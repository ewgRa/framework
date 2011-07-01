<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NumberToString extends Singleton
	{
		const MILLIARD = 1000000000;
		const MILLION = 1000000;
		const THOUSAND = 1000;
		const RUBL = 1;
		const KOPEYKA = '0.01';

		private $words = array(
			0 => 'ноль',
			1 => array( 'один', self::THOUSAND => 'одна', self::KOPEYKA => 'одна' ),
			2 => array( 'два', self::THOUSAND => 'две', self::KOPEYKA => 'две' ),
			3 => 'три',
			4 => 'четыре',
			5 => 'пять',
			6 => 'шесть',
			7 => 'семь',
			8 => 'восемь',
			9 => 'девять',
			10 => 'десять',
			11 => 'одиннацать',
			12 => 'двенадцать',
			13 => 'тринадцать',
			14 => 'четырнадцать',
			15 => 'пятнадцать',
			16 => 'шестнадцать',
			17 => 'семнадцать',
			18 => 'восемнадцать',
			19 => 'девятнадцать',
			20 => 'двадцать',
			30 => 'тридцать',
			40 => 'сорок',
			50 => 'пятьдесят',
			60 => 'шестьдесят',
			70 => 'семьдесят',
			80 => 'восемдесят',
			90 => 'девяносто',
			100 => 'сто',
			200 => 'двести',
			300 => 'триста',
			400 => 'четыреста',
			500 => 'пятьсот',
			600 => 'шестьсот',
			700 => 'семьсот',
			800 => 'восемьсот',
			900 => 'девятьсот'
		);

		private $symanticLink = array(
			array(1),
			array(2, 3, 4),
			array(5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 30, 40, 50, 60, 70, 80, 90, 100, 200, 300, 400, 500, 600, 700, 800, 900)
		);

		private $dimensionWords = array();

		private $dimensionWordsKopeyka = array();

		private $lastNumber = null;

		/**
		 * @return NumberToString
		 * method needed for methods hinting
		 */
		public static function me()
		{
			return parent::me();
		}

		function __construct()
		{
			$this->dimensionWords = array(
				self::RUBL => array( 'рубль' => $this->symanticLink[0], 'рубля' => $this->symanticLink[1], 'рублей' => $this->symanticLink[2] ),
				self::THOUSAND => array( 'тысяча' => $this->symanticLink[0], 'тысячи' => $this->symanticLink[1], 'тысяч' => $this->symanticLink[2] ),
				self::MILLION => array( 'миллион' => $this->symanticLink[0], 'миллиона' => $this->symanticLink[1], 'миллионов' => $this->symanticLink[2] ),
				self::MILLIARD => array( 'миллиард' => $this->symanticLink[0], 'миллиарда' => $this->symanticLink[1], 'миллиардов' => $this->symanticLink[2] )
			);

			$this->dimensionWordsKopeyka = array(
				self::KOPEYKA => array( 'копейка' => $this->symanticLink[0], 'копейки' => $this->symanticLink[1], 'копеек' => $this->symanticLink[2] ),
				self::THOUSAND => array( 'тысяча' => $this->symanticLink[0], 'тысячи' => $this->symanticLink[1], 'тысяч' => $this->symanticLink[2] ),
				self::MILLION => array( 'миллион' => $this->symanticLink[0], 'миллиона' => $this->symanticLink[1], 'миллионов' => $this->symanticLink[2] ),
				self::MILLIARD => array( 'миллиард' => $this->symanticLink[0], 'миллиарда' => $this->symanticLink[1], 'миллиардов' => $this->symanticLink[2] )
			);
		}

		public function toString($number)
		{
			$n = explode('.', $number);

			$r = $this->process($n[0], $this->dimensionWords);

			if(array_key_exists(1, $n)) {
				if(strlen($n[1]) == 1)
					$n[1] *= 10;

				$r = array_merge($r, $this->process($n[1]/100, $this->dimensionWordsKopeyka));
			}

			return $r;
		}

		private function process($number, $dimensionWords)
		{
			$result = array();

			end($dimensionWords);

			for($i=0; $i<count($dimensionWords); $i++) {
				$dimension = key($dimensionWords);

				$count = floor($number/$dimension);

				if($count == 0) {
					prev($dimensionWords);
					continue;
				}

				$this->lastNumber = null;

				$result = array_merge(
					$result,
					$this->getString($count, $dimension)
				);

				$dimensionResult =
					$this->getDimensionalityString($this->lastNumber, $dimension, $dimensionWords);

				if($dimensionResult)
					$result = array_merge($result, array($dimensionResult));

				$number -= $count*$dimension;
				prev($dimensionWords);
			}

			return $result;
		}

		private function getString($number, $dec)
		{
			$result = array();

			if($number == 0)
				return $result;

			if(array_key_exists((string)$number, $this->words)) {
				$result[] = $this->getSingleString($number, $dec);
				$this->lastNumber = $number;
			} else {
				$step = 0;

				if ($number >= self::MILLIARD)
					$step = self::MILLIARD;
				elseif ($number >= self::MILLION)
					$step = self::MILLION;
				elseif ($number >= self::THOUSAND)
					$step = self::THOUSAND;
				elseif ($number >= 100)
					$step = 100;
				elseif ($number >= 10)
					$step = 10;
				elseif ($number >= 1)
					$step = 1;
				else
					Assert::isUnreachable();

				$count = floor($number/$step);

				$result = array_merge(
					$result,
					$this->getString(
						$step < self::THOUSAND
							? (string)$count*$step
							: (string)$count,
						$dec
					)
				);

				$number -= $count*$step;
				$result = array_merge($result, $this->getString($number, $dec));
			}

			return $result;
		}

		private function getSingleString($number, $dec)
		{
			$result = '';

			if(
				is_array($this->words[$number])
				&& array_key_exists($dec, $this->words[$number])
			) {
				$result = $this->words[$number][$dec];
			} elseif(
				is_array($this->words[$number])
				&& array_key_exists(0, $this->words[$number])
			) {
				$result = $this->words[$number][0];
			} else
				$result = $this->words[$number];

			return $result;
		}

		private function getDimensionalityString($number, $dec, $dimensionWords)
		{
			if(array_key_exists($dec, $dimensionWords)) {
				foreach($dimensionWords[$dec] as $k => $v) {
					if(in_array($number, $v))
						return $k;
				}
			}

			return null;
		}
	}
?>