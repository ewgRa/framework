<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DateTimeRange
	{
		/**
		 * @var DateTime
		 */
		private $start 	= null;

		/**
		 * @var DateTime
		 */
		private $end 	= null;

		public static function create()
		{
			return new self;
		}

		public function setStart(DateTime $dateTime)
		{
			$this->start = $dateTime;
			return $this;
		}

		public function getStart()
		{
			return $this->start;
		}

		public function setEnd(DateTime $dateTime)
		{
			$this->end = $dateTime;
			return $this;
		}

		public function getEnd()
		{
			return $this->end;
		}

		/**
		 * @deprecated use isEqMonth instead
		 */
		public function isOneMonth()
		{
			return $this->isEqMonth();
		}

		public function isEqMonth()
		{
			return
				$this->start->getYear() == $this->end->getYear()
				&& $this->start->getMonth() == $this->end->getMonth();
		}
	}
?>