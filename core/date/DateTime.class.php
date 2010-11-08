<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DateTime extends \DateTime
	{
		public static function create($time)
		{
			return new self($time);
		}
		
		public function getMonth()
		{
			return $this->format('m');
		}
	}
?>