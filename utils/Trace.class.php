<?php
	/* $Id$ */

	class Trace
	{
		private $line;
		private $file;
		
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @param integer $line
		 * @return Trace
		 */
		public function setLine($line)
		{
			$this->line = $line;
			return $this;
		}

		/**
		 * @return integer
		 */
		public function getLine()
		{
			return $this->line;
		}
		
		/**
		 * @param string $file
		 * @return Trace
		 */
		public function setFile($file)
		{
			$this->file = $file;
			return $this;
		}
		
		/**
		 * @return string
		 */
		public function getFile()
		{
			return $this->file;
		}
	}
?>