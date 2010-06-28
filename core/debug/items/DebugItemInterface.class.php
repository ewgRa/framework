<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface DebugItemInterface
	{
		/**
		 * @return DebugItemInterface
		 */
		public static function create();
		
		public function setStartTime($time);
		
		public function getStartTime();
		
		/**
		 * @return DebugItemInterface
		 */
		public function setEndTime($time);
		
		public function getEndTime();
		
		/**
		 * @return DebugItemInterface
		 */
		public function setTrace(array $trace);
		
		/**
		 * @return array
		 */
		public function getTrace();
		
		/**
		 * @return DebugItemInterface
		 */
		public function setData($data);
		
		public function getData();
	}
?>