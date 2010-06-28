<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseDebugItem implements DebugItemInterface
	{
		private $trace 		= null;

		private $data  		= null;
		
		private $startTime  = null;
		private $endTime	= null;
		
		/**
		 * @return BaseDebugItem
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
		 * @return BaseDebugItem
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
		 * @return BaseDebugItem
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
		 * @return BaseDebugItem
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