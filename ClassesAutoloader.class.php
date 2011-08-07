<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ClassesAutoloader extends Singleton
	{
		const VARIOUS_NAMESPACE = null;
		const ROOT_NAMESPACE = '\\';

		const CLASS_FILE_EXTENSION	= '.class.php';

		private $foundClasses 		= array();

		private $searchDirectories = array(
			self::ROOT_NAMESPACE 	=> array(),
			self::VARIOUS_NAMESPACE => array()
		);

		private $namespaces	= array();

		private $classMapChanged 	= false;

		/**
		 * @return ClassesAutoloader
		 * method needed for methods hinting
		 */
		public static function me()
		{
			return parent::me();
		}

		public function isClassMapChanged()
		{
			return $this->classMapChanged;
		}

		/**
		 * @return ClassesAutoloader
		 */
		public function addSearchDirectory(
			$directory,
			$namespace = self::VARIOUS_NAMESPACE
		) {
			if (!isset($this->searchDirectories[$namespace])) {
				$this->searchDirectories[$namespace] = array();
				$this->recalcNamespaces();
			}

			$this->searchDirectories[$namespace][] = $directory;

			return $this;
		}

		/**
		 * @return ClassesAutoloader
		 */
		public function load($className)
		{
			if ($this->classExists($className))
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
				$this->isFound($className)
				&& (
					!$this->classExists($className)
					|| !$classFile
				)
			) {
				$this->dropFound($className);
				$this->classMapChanged = true;
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

		public function loadAllClasses()
		{
			$searchDirectories = array();

			foreach ($this->searchDirectories as $namespace => $directories)
				$searchDirectories = array_merge($searchDirectories, $directories);

			$this->baseLoadAllClasses(array_unique($searchDirectories));

			return $this;
		}

		private function baseLoadAllClasses(array $searchDirectories)
		{
			foreach ($searchDirectories as $directory) {
				foreach (glob($directory.DIRECTORY_SEPARATOR.'*') as $fileName) {
					if (is_dir($fileName))
						$this->{__FUNCTION__}(array($fileName));
					elseif (strpos($fileName, self::CLASS_FILE_EXTENSION))
						require_once($fileName);
				}
			}

			return $this;
		}

		private function findClassFile($className)
		{
			$searchDirectories = array();

			$nameParts = explode('\\', $className);

			$classNameWithoutNamespace = array_pop($nameParts);

			$namespace = join('\\', $nameParts);

			if ($namespace) {
				foreach($this->namespaces as $probablyNamespace) {
					if (
						in_array(
							$probablyNamespace,
							array(self::ROOT_NAMESPACE, self::VARIOUS_NAMESPACE)
						)
					)
						continue;

					if (
						$namespace == $probablyNamespace
						|| strpos($namespace, $probablyNamespace.'\\') === 0
					) {
						$searchDirectories =
							$this->searchDirectories[$probablyNamespace];

						break;
					}
				}
			} else
				$searchDirectories = $this->searchDirectories[self::ROOT_NAMESPACE];

			$searchDirectories =
				array_unique(
					array_merge(
						$searchDirectories,
						$this->searchDirectories[self::VARIOUS_NAMESPACE]
					)
				);

			return
				$this->baseFindClassFile(
					$className,
					$classNameWithoutNamespace,
					$searchDirectories
				);
		}

		private function baseFindClassFile(
			$className,
			$classNameWithoutNamespace,
			array $searchDirectories
		) {
			$result = null;

			foreach ($searchDirectories as $directory) {
				foreach (
					glob($directory.DIRECTORY_SEPARATOR.'*') as $fileName
				) {
					if (is_dir($fileName)) {
						$result =
							$this->{__FUNCTION__}(
								$className,
								$classNameWithoutNamespace,
								array($fileName)
							);

						if ($result)
							break 2;
					} elseif (
						is_file($fileName)
						&& basename($fileName)
							== $classNameWithoutNamespace.self::CLASS_FILE_EXTENSION
					) {
						require_once($fileName);

						if ($this->classExists($className)) {
							$result = $fileName;
							break 2;
						}
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

		private function recalcNamespaces()
		{
			$this->namespaces = array_keys($this->searchDirectories);
			sort($this->namespaces);
			$this->namespaces = array_reverse($this->namespaces);
			return $this;
		}

		private function classExists($className)
		{
			return class_exists($className) || interface_exists($className);
		}
	}
?>