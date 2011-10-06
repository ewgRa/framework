<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class Month extends Enumeration
	{
		const JANUARY 		= 1;
		const FEBRUARY 		= 2;
		const MARCH 		= 3;
		const APRIL 		= 4;
		const MAY 			= 5;
		const JUNE 			= 6;
		const JULY 			= 7;
		const AUGUST 		= 8;
		const SEPTEMBER 	= 9;
		const OCTOBER 		= 10;
		const NOVEMBER 		= 11;
		const DECEMBER 		= 12;

		protected $names = array(
			self::JANUARY 		=> 'January',
			self::FEBRUARY 		=> 'February',
			self::MARCH 		=> 'March',
			self::APRIL 		=> 'April',
			self::MAY 			=> 'May',
			self::JUNE 			=> 'June',
			self::JULY 			=> 'July',
			self::AUGUST 		=> 'August',
			self::SEPTEMBER 	=> 'September',
			self::OCTOBER 		=> 'October',
			self::NOVEMBER 		=> 'November',
			self::DECEMBER 		=> 'December'
		);

		/**
		 * @return Month
		 * NOTE: method for hint
		 */
		public static function create($id)
		{
			return parent::create($id);
		}
	}
?>