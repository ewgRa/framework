<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	class DebugItem
	{
		const ENGINE_ECHO	= 1;
		const DATABASE		= 2;
		
		private $trace = null;
		private $type  = null;
		private $data  = null;
		
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