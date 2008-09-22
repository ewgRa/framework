<?php
	/* $Id$ */
	
	final class FileBasedCache extends Cache
	{
		private $cacheDir 			= null;

		/**
		 * @return FileBasedCache
		 */
		public static function create()
		{
			return new self;
		}
		
		public function createTicket($ticketAlias = null)
		{
			return FileBasedCacheTicket::create()->
				setCacheInstance($this)->
				fillParams($this->getTicketParams($ticketAlias));
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
		
		public function get(CacheTicket $ticket)
		{
			if($this->isDisabled())
			{
				$this->expired();
				return null;
			}
			
			$actualTime = $ticket->getActualTime();

			if(!$actualTime)
				$actualTime = time();
			
			$result = null;
			
			$fileName = $this->compileFileName(
				$ticket->getKey(), $ticket->getPrefix()
			);
			
			if(!file_exists($fileName))
			{
				$ticket->expired();
			}
			elseif(filemtime($fileName) < $actualTime)
			{
				unlink($fileName);
				$ticket->expired();
			}
			else
			{
				$ticket->actual();
				$result = unserialize(file_get_contents($fileName));
			}

			return $result;
		}

		public function set(CacheTicket $ticket)
		{
			if($this->isDisabled())
				return null;

			$fileName = $this->compileFileName(
				$ticket->getKey(), $ticket->getPrefix()
			);
				
			if(!$fileName)
				throw new Exception('no key');
			
			$this->createPreDirs($fileName);
			
			file_put_contents($fileName, serialize($ticket->getData()));
			touch($fileName, $ticket->getLifeTime());
			
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

			if(!file_exists($directory))
				mkdir($directory, 0777, true);
							
			return true;
		}
	}
?>