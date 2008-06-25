<?php
	final class FileBasedCache extends Cache
	{
		const OS_WIN = 'WIN';

		private $cacheDir 			= null;
		private $fileName			= null;

		/**
		 * @return FileBasedCache
		 */
		public static function create()
		{
			return new self;
		}
		
		public function setCacheDir($cacheDir)
		{
			$this->cacheDir = $cacheDir;
			return $this;
		}
		
		public function getCacheDir()
		{
			return $this->cacheDir;
		}
		
		private function getFileName()
		{
			return $this->fileName;
		}
		
		private function setFileName($filename)
		{
			$this->fileName = $filename;
			return $this;
		}
		
		private function dropFileName()
		{
			$this->fileName = null;
			return $this;
		}
		
		public function get($key, $prefix = null, $actualTime = null)
		{
			if(!$actualTime)
				$actualTime = time();
			
			if($this->isDisabled())
			{
				$this->expired();
				return null;
			}
			
			$result = null;
			$fileName = $this->compileFileName($key, $prefix);
			$this->setFileName($fileName);
			
			if(!file_exists($fileName))
			{
				$this->expired();
			}
			elseif(filemtime($fileName) < $actualTime)
			{
				unlink($fileName);
				$this->expired();
			}
			else
			{
				$this->actual();
				$result = unserialize(file_get_contents($fileName));
			}

			return $result;
		}

		public function set(
			$data, $lifeTillTime = null,
			$key = null, $prefix = null
		)
		{
			if($this->isDisabled())
				return null;

			$fileName = $this->getFileName();
			
			if(!is_null($key))
				$fileName = $this->compileFileName($key, $prefix);

			if(!$fileName)
				throw new Exception('no key');
			
			if(is_null($lifeTillTime))
				$lifeTillTime = time() + $this->getDefaultLifeTime();

			$this->createPreDirs( $fileName );
			
			file_put_contents($fileName, serialize($data));
			touch( $fileName, $lifeTillTime);
			
			return $this;
		}

		private function compileFileName($key, $prefix)
		{
			$fileName = md5(serialize($key));
			
			$resultArray = array();
			$resultArray[] = $this->getCacheDir();
			
			if($prefix)
				$resultArray[] = $prefix;
				
			$resultArray[] = $this->compilePreDirs($fileName);
			$resultArray[] = $fileName;
			
			return join(DIRECTORY_SEPARATOR, $resultArray);
		}

		private function compilePreDirs(
			$fileName,
			$directoryCount = 2,
			$symbolCount = 2
		)
		{
			$resultArray = array();
			
			for($i=0; $i < $directoryCount; $i++ )
			{
				$resultArray[] = substr(
					$fileName,
					$i * $symbolCount,
					$symbolCount
				);
			}
			
			return join(DIRECTORY_SEPARATOR, $resultArray);
		}

		private function createPreDirs($fileName)
		{
			$directory = dirname($fileName);

			if(strtoupper(substr(PHP_OS, 0, 3)) === self::OS_WIN)
			{
				exec('mkdir "' . $directory . '"');
			} else {
				exec('mkdir -p "' . $directory . '"');
			}
			
			return true;
		}
	}
?>