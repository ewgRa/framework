<?php
	/* $Id$ */
	
	class DebugItem
	{
		const ENGINE_ECHO	= 1;
		const DATABASE		= 2;
		
		private $trace = null;
		private $type = null;
		private $data = null;
		
		/**
		 * @return DebugItem
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @param array $trace
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
		 * @param integer $type
		 * @return DebugItem
		 */
		public function setType($type)
		{
			$this->type = $type;
			return $this;
		}
		
		/**
		 * @return integer
		 */
		public function getType()
		{
			return $this->type;
		}

		/**
		 * @param string $data
		 * @return DebugItem
		 */
		public function setData($data)
		{
			$this->data = $data;
			return $this;
		}
		
		/**
		 * @return string
		 */
		public function getData()
		{
			return $this->data;
		}
	}
?>