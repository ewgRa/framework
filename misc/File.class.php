<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class File
	{
		private $path = null;
		
		/**
		 * @return File
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return File
		 */
		public function setPath($path)
		{
			$this->path = $path;
			return $this;
		}
		
		public function getPath()
		{
			return $this->path;
		}
		
		public function getContent()
		{
			return file_get_contents($this->getPath());
		}
	}
?>