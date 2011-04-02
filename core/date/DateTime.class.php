<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DateTime extends \DateTime
	{
		public static function makeNow()
		{
			return self::createFromTimestamp(time());
		}

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
			return $this->format('d');
		}

		public function getMonth()
		{
			return $this->format('n');
		}

		public function getYear()
		{
			return $this->format('Y');
		}
	}
?>