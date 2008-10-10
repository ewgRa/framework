<?php
	/* $Id$ */

	$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'patterns'
			. DIRECTORY_SEPARATOR . 'Singleton.class.php';
	
	if(!class_exists('Singleton', false) && file_exists($file))
		require_once($file);
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	final class ClassesAutoloader extends Singleton
	{
		const CLASS_FILE_EXTENSION	= '.class.php';
		
		private $foundClasses 		= array();
		private $searchDirectories	= array();
		private $cacheTicket		= null;

		/**
		 * @return ClassesAutoloader
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		/**
		 * @return ClassesAutoloader
		 */
		public function setCacheTicket(CacheTicket $ticket)
		{
			$this->cacheTicket = $ticket;
			return $this;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function getCacheTicket()
		{
			return $this->cacheTicket;
		}
		
		public function hasCacheTicket()
		{
			return !is_null($this->cacheTicket);
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
			$this->searchDirectories = array_merge(
				$this->searchDirectories,
				$searchDirectories
			);
			
			return $this;
		}
		
		/**
		 * @return ClassesAutoloader
		 */
		public function load($className)
		{
			if(class_exists($className))
				return $this;
			
			$classFile = $this->getFoundClassFile($className);
			
			if(!file_exists($this->getFoundClassFile($className)))
			{
				$classFile = $this->findClassFile($className);

				if($classFile)
					$this->setClassFile($className, $classFile)->saveCache();
			}

			if($classFile)
				require_once($classFile);

			if(!class_exists($className) || !$classFile)
				$this->dropFound($className)->saveCache();
			
			return $this;
		}
				
		/**
		 * @return ClassesAutoloader
		 */
		public function loadCache()
		{
			if(
				$this->hasCacheTicket()
				&& $foundClasses = $this->getCacheTicket()->restoreData()->getData()
			)
			{
				$this->setFoundClasses($foundClasses);
			}
			
			return $this;
		}

		private function findClassFile($className, array $searchDirectories = null)
		{
			$result = null;
			
			if(!$searchDirectories)
				$searchDirectories = $this->getSearchDirectories();
			
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
							break 2;
					}
					elseif(
						is_file($fileName)
						&& basename($fileName)
							== $className . self::CLASS_FILE_EXTENSION
					)
					{
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
		
		/**
		 * @return ClassesAutoloader
		 */
		private function saveCache()
		{
			if($this->hasCacheTicket())
			{
				$this->getCacheTicket()->
					setData($this->getFoundClasses())->
					storeData();
			}
			
			return $this;
		}

		/**
		 * @return ClassesAutoloader
		 */
		private function setFoundClasses(array $foundClasses)
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
			return $this->isFound($className)
				? $this->foundClasses[$className]
				: null;
		}
	}
?>