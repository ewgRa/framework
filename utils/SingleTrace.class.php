<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SingleTrace
	{
		private $line = null;
		private $file = null;
		
		/**
		 * @return SingleTrace
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return SingleTrace
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
		 * @return SingleTrace
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