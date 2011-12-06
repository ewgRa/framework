<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DateTime extends \DateTime
	{
		/**
		 * @return DateTime
		 */
		public static function makeNow()
		{
			return self::createFromTimestamp(time());
		}

		/**
		 * @return DateTime
		 * NOTE: method for meta builder
		 */
		public static function createFromString($string)
		{
			return self::create($string);
		}

		/**
		 * @return DateTime
		 */
		public static function createFromTimestamp($timestamp)
		{
			$dateTime = new self();
			$dateTime->setTimestamp($timestamp);

			return $dateTime;
		}

		public static function create($time = null)
		{
			return new self($time);
		}

		public function getDay()
		{
			return (int)$this->format('d');
		}

		public function getMonth()
		{
			return (int)$this->format('n');
		}

		public function getYear()
		{
			return (int)$this->format('Y');
		}

		public function __toString()
		{
			return $this->format(self::ISO8601);
		}
	}
?>