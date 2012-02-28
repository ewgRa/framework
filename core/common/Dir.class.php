<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Dir
	{
		const PERMISSIONS = 0775;

		private $path = null;

		/**
		 * @return Dir
		 */
		public static function create()
		{
			return new self;
		}

		public static function deleteDir($dir)
		{
			self::cleanDir($dir);
			rmdir($dir);
		}

		public static function cleanDir($dir)
		{
			$files = glob(str_replace('\\', '\\\\', $dir).DIRECTORY_SEPARATOR.'*');

			foreach ($files as $file) {
				if(is_dir($file))
					self::deleteDir($file);
				else
					unlink($file);
			}
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

		public function clean()
		{
			self::cleanDir($this->getPath());
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
			mkdir($this->getPath(), self::PERMISSIONS, true);
			umask($umask);
			return $this;
		}

		public function getFiles()
		{
			$result = array();

			foreach(new \FilesystemIterator($this->getPath()) as $file) {
				if ($file->isFile())
					$result[] = File::create()->setPath($file->getPathName());
			}

			return $result;
		}
	}
?>