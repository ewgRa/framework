<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * FIXME: finalize, and think about cms page
	*/
	class DebugItem
	{
		const ENGINE_ECHO	= 1;
		const DATABASE		= 2;
		const REQUEST		= 3;
		const CACHE			= 4;
		
		private $trace 		= null;
		
		# FIXME: enumeration
		private $type  		= null;
		
		private $data  		= null;
		private $startTime  = null;
		private $endTime	= null;
		
		/**
		 * @return DebugItem
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return DebugItem
		 */
		public function setStartTime($time)
		{
			$this->startTime = $time;
			return $this;
		}
		
		public function getStartTime()
		{
			return $this->startTime;
		}
		
		/**
		 * @return DebugItem
		 */
		public function setEndTime($time)
		{
			$this->endTime = $time;
			return $this;
		}
		
		public function getEndTime()
		{
			return $this->endTime;
		}
		
		/**
		 * @return DebugItem
		 */
		public function setTrace(array $trace)
		{
			$this->trace = $trace;
			return $this;
		}
		
		/**
		 * @return array
		 */
		public function getTrace()
		{
			return $this->trace;
		}
		
		/**
		 * @return DebugItem
		 */
		public function dropTrace()
		{
			$this->trace = null;
			return $this;
		}
		
		/**
		 * @return DebugItem
		 */
		public function setType($type)
		{
			$this->type = $type;
			return $this;
		}
		
		public function getType()
		{
			return $this->type;
		}

		/**
		 * @return DebugItem
		 */
		public function setData($data)
		{
			$this->data = $data;
			return $this;
		}
		
		public function getData()
		{
			return $this->data;
		}
	}
?>