<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ClassesAutoloader extends Singleton
	{
		const CLASS_FILE_EXTENSION	= '.class.php';
		
		private $foundClasses 		= array();
		private $searchDirectories	= array();
		
		private $classMapChanged 	= false;
		
		/**
		 * @return ClassesAutoloader
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public function isClassMapChanged()
		{
			return $this->classMapChanged;
		}
		
		/**
		 * @return ClassesAutoloader
		 */
		public function setSearchDirectories(array $searchDirectories)
		{
			$this->searchDirectories = $searchDirectories;
			return $this;
		}
		
		public function getSearchDirectories()
		{
			return $this->searchDirectories;
		}
		
		/**
		 * @return ClassesAutoloader
		 */
		public function addSearchDirectories(array $searchDirectories)
		{
			$this->searchDirectories =
				array_unique(
					array_merge($this->searchDirectories, $searchDirectories)
				);
			
			return $this;
		}
		
		/**
		 * @return ClassesAutoloader
		 */
		public function load($className)
		{
			if (class_exists($className) || interface_exists($className))
				return $this;
			
			$classFile = $this->getFoundClassFile($className);
			
			if (!$classFile || !file_exists($classFile)) {
				$classFile = $this->findClassFile($className);
				
				if ($classFile) {
					$this->setClassFile($className, $classFile);
					$this->classMapChanged = true;
				}
			}

			if ($classFile)
				require_once($classFile);

			if (
				(!class_exists($className) && !interface_exists($className))
				|| !$classFile
			) {
				$this->classMapChanged = true;
				$this->dropFound($className);
			}
			
			return $this;
		}
				
		/**
		 * @return ClassesAutoloader
		 */
		public function setFoundClasses(array $foundClasses)
		{
			$this->foundClasses = $foundClasses;
			return $this;
		}
		
		public function getFoundClasses()
		{
			return $this->foundClasses;
		}
		
		
		public function getFoundClassFile($className)
		{
			return
				$this->isFound($className)
					? $this->foundClasses[$className]
					: null;
		}
		
		private function findClassFile(
			$className,
			array $searchDirectories = null
		) {
			$result = null;
			
			if (!$searchDirectories)
				$searchDirectories = $this->getSearchDirectories();
			
			foreach ($searchDirectories as $directory) {
				foreach (
					glob($directory . DIRECTORY_SEPARATOR . '*') as $fileName
				) {
					if (is_dir($fileName)) {
						$result =
							$this->{__FUNCTION__}($className, array($fileName));
						
						if ($result)
							break 2;
					} elseif (
						is_file($fileName)
						&& basename($fileName)
							== $className . self::CLASS_FILE_EXTENSION
					) {
						$result = $fileName;
						break 2;
					}
				}
			}
			
			return $result;
		}
		
		/**
		 * @return ClassesAutoloader
		 */
		private function setClassFile($className, $classFile)
		{
			$this->foundClasses[$className] = $classFile;
			return $this;
		}
		
		/**
		 * @return ClassesAutoloader
		 */
		private function dropFound($className)
		{
			unset($this->foundClasses[$className]);
			return $this;
		}
		
		private function isFound($className)
		{
			return isset($this->foundClasses[$className]);
		}
	}
?>