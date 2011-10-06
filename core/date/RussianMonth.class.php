<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class RussianMonth extends Month
	{
		protected $names = array(
			self::JANUARY 		=> 'Январь',
			self::FEBRUARY 		=> 'Февраль',
			self::MARCH 		=> 'Март',
			self::APRIL 		=> 'Апрель',
			self::MAY 			=> 'Май',
			self::JUNE 			=> 'Июнь',
			self::JULY 			=> 'Июль',
			self::AUGUST 		=> 'Август',
			self::SEPTEMBER 	=> 'Сентябрь',
			self::OCTOBER 		=> 'Октябрь',
			self::NOVEMBER 		=> 'Ноябрь',
			self::DECEMBER 		=> 'Декабрь'
		);

		protected $genitiveNames = array(
			self::JANUARY 		=> 'Января',
			self::FEBRUARY 		=> 'Февраля',
			self::MARCH 		=> 'Марта',
			self::APRIL 		=> 'Апреля',
			self::MAY 			=> 'Мая',
			self::JUNE 			=> 'Июня',
			self::JULY 			=> 'Июля',
			self::AUGUST 		=> 'Августа',
			self::SEPTEMBER 	=> 'Сентября',
			self::OCTOBER 		=> 'Октября',
			self::NOVEMBER 		=> 'Ноября',
			self::DECEMBER 		=> 'Декабря'
		);

		/**
		 * @return RussianMonth
		 * NOTE: method for hint
		 */
		public static function create($id)
		{
			return parent::create($id);
		}

		public function getGenitiveName()
		{
			return $this->genitiveNames[$this->getId()];
		}

		public function getLowerGenitiveName()
		{
			return StringUtils::toLower($this->getGenitiveName());
		}
	}
?>