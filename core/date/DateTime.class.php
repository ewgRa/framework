<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DateTime extends \DateTime
	{
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
			return $this->format('m');
		}

		public function getYear()
		{
			return $this->format('Y');
		}
	}
?>