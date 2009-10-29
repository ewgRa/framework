<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Dir
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
		
		public function delete()
		{
			return self::deleteDir($this->getPath());
		}
		
		public static function deleteDir($dir)
		{
			$files = glob($dir . DIRECTORY_SEPARATOR . '*');
			
			foreach ($files as $file) {
				if(is_dir($file))
					self::deleteDir($file);
				elseif(is_file($file))
					unlink($file);
			}

			rmdir($dir);
		}
	}
?>