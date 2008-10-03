<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	class Trace
	{
		private $line = null;
		private $file = null;
		
		/**
		 * @return Trace
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return Trace
		 */
		public function setLine($line)
		{
			$this->line = $line;
			return $this;
		}

		public function getLine()
		{
			return $this->line;
		}
		
		/**
		 * @return Trace
		 */
		public function setFile($file)
		{
			$this->file = $file;
			return $this;
		}
		
		public function getFile()
		{
			return $this->file;
		}
	}
?>