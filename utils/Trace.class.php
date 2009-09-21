<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * FIXME: rename to single trace
	*/
	final class Trace
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