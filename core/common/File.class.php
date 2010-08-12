<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class File
	{
		const PERMISSIONS = 0664;
		
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

		public function setContent($content)
		{
			return file_put_contents($this->getPath(), $content);
		}
		
		public function isExists()
		{
			return file_exists($this->getPath());
		}
		
		/**
		 * @return File
		 */
		public function delete()
		{
			Assert::isTrue($this->isExists());
			unlink($this->getPath());
			return $this;
		}
		
		/**
		 * @return File
		 */
		public function moveTo(File $file)
		{
			Assert::isTrue($this->isExists());
			rename($this->getPath(), $file->getPath());
			return $this;
		}
		
		/**
		 * @return File
		 */
		public function copyTo(File $file)
		{
			Assert::isTrue($this->isExists());
			copy($this->getPath(), $file->getPath());
			return $this;
		}
		
		public function getBaseName()
		{
			return basename($this->getPath());
		}
		
		public function getDir()
		{
			return 
				Dir::create()->
				setPath(pathinfo($this->getPath(), PATHINFO_DIRNAME));
		}
		
		public function chmod($permission)
		{
			chmod($this->getPath(), $permission);
			return $this;
		}
	}
?>