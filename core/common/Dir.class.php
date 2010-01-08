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
		 * @return Dir
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return Dir
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
			self::deleteDir($this->getPath());
			return $this;
		}
		
		public function isExists()
		{
			return file_exists($this->getPath());
		}
		
		/**
		 * @return Dir
		 */
		public function make()
		{
			$umask = umask(0);
			mkdir($this->getPath(), FileBasedCache::DIR_PERMISSIONS, true);
			umask($umask);
			return $this;
		}
		
		public static function deleteDir($dir)
		{
			$files = glob($dir . DIRECTORY_SEPARATOR . '*');
			
			$function = __FUNCTION__;
			
			foreach ($files as $file) {
				if(is_dir($file))
					self::$function($file);
				else
					unlink($file);
			}

			rmdir($dir);
		}
	}
?>