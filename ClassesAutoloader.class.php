<?php
	// TODO: runtime cache!!!
	// TODO: packets
	class ClassesAutoloader extends Singleton
	{
		const CLASS_FILE_EXTENSION	= '.class.php';
		
		private $foundClasses 		= array();
		private $searchDirectories	= array();
		private $cacheRealization		= null;		
		private static $instance = null;

		/**
		 * @return ClassesAutoloader
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__, self::$instance);
		}

		public function load($className)
		{
			if(class_exists($className))
				return $this;
			
			$classFile = $this->getFoundClassFile($className);
			
			if(!file_exists($this->getFoundClassFile($className)))
			{
				$classFile = $this->findClassFile($className);

				if($classFile)
				{
					$this->setClassFile($className, $classFile);
					$this->saveCache();
				}
			}

			if($classFile)
			{
				require_once($classFile);
				// FIXME: if(!class_exists($className))?
			}
			else
			{
				$this->dropFound($classFile);
				$this->saveCache();
			}
			
			return $this;
		}

		public function getSearchDirectories()
		{
			return $this->searchDirectories;	
		}
		
		public function setSearchDirectories(array $searchDirectories)
		{
			$this->searchDirectories = $searchDirectories;
			return $this;	
		}
		
		public function addSearchDirectories(array $searchDirectories)
		{
			$this->searchDirectories = array_merge(
				$this->searchDirectories,
				$searchDirectories
			);
			
			return $this;	
		}
		
		public function findClassFile($className, $searchDirectories = null)
		{
			$result = null;
			
			if(!$searchDirectories)
			{
				$searchDirectories = $this->getSearchDirectories();
			}
			
			foreach($searchDirectories as $directory)
			{
				foreach(glob($directory . DIRECTORY_SEPARATOR . '*') as $fileName)
				{
					if(is_dir($fileName))
					{
						$result = $this->findClassFile(
							$className,
							array($fileName)
						);
						
						if($result)
							break;
					}
					elseif(
						is_file($fileName)
						&& basename($fileName)
							== $className . self::CLASS_FILE_EXTENSION
					)
					{
						$result = $fileName;
						break;
					}
				}
				
				if($result)
					break;
			}
			
			return $result;
		}
		
		public function setClassFile($className, $classFile)
		{
			$this->foundClasses[$className] = $classFile;
		}
		
		public function setCacheRealization($realization)
		{
			$this->cacheRealization = $realization;
			return $this;
		}
		
		public function getCacheRealization()
		{
			return $this->cacheRealization;
		}
		
		public function loadCache()
		{
			if($this->getCacheRealization())
			{
				$this->setFoundClasses(
					$this->getCacheRealization()->getData(
						$this->getSearchDirectories(),
						'autoloader'
					)
				);
			}
		}

		public function dropFound($className)
		{
			unset($this->foundClasses[$className]);
			return $this;
		}
		
		private function saveCache()
		{
			if($this->getCacheRealization())
			{
				$this->getCacheRealization()->setData(
					$this->getFoundClasses(),
					null,
					$this->getSearchDirectories(),
					'autoloader'
					
				);
			}			
		}

		private function setFoundClasses($foundClasses)
		{
			$this->foundClasses = $foundClasses;
			return $this;
		}
		
		private function getFoundClasses()
		{
			return $this->foundClasses;
		}
		
		private function isFound($className)
		{
			return isset($this->foundClasses[$className]);
		}
		
		private function getFoundClassFile($className)
		{
			if($this->isFound($className))
			{
				return $this->foundClasses[$className];
			}
			
			return null;
		}
	}
		
	function __autoload($className)
	{
		ClassesAutoloader::me()->load($className);
	}
?>