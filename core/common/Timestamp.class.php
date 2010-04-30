<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Timestamp
	{
		private $time = null;

		/**
		 * @return Timestamp
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return Timestamp
		 */
		public static function createNow()
		{
			return self::create()->setTime(time());
		}
		
		/**
		 * @return Timestamp
		 */
		public static function createFromString($string)
		{
			$timestamp = self::create();
			
			$timestamp->setTime(strtotime($string));
			
			return $timestamp;
		}
		
		/**
		 * @return Timestamp
		 */
		public function setTime($time)
		{
			$this->time = $time;
			return $this;
		}
		
		public function getTime()
		{
			return $this->time;
		}
		
		public function __toString()
		{
			return date('Y-m-d H:i:s', $this->getTime());
		}
	}
?>