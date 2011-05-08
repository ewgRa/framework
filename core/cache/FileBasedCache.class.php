<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class FileBasedCache extends BaseCache
	{
		const FILE_PERMISSIONS 	= 0664;
		const DIR_PERMISSIONS 	= 0775;

		private $cacheDir = null;

		/**
		 * @return FileBasedCache
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return FileBasedCache
		 */
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
			$result = null;

			$fileName = $this->compileKey($ticket);

			if (!file_exists($fileName))
				$ticket->expired();
			else {
				$fileModifiedTime = filemtime($fileName);

				if ($fileModifiedTime < time()) {
					$ticket->
						setExpiredTime(null)->
						expired();

					$this->dropByKey($fileName);
				} else {
					$ticket->
						setExpiredTime($fileModifiedTime)->
						actual();

					$result = unserialize(file_get_contents($fileName));
				}
			}

			$this->notifyObservers(
				self::GET_TICKET_EVENT,
				Model::create()->
				set('ticket', $ticket)
			);

			return $result;
		}

		/**
		 * @return FileBasedCache
		 */
		public function set(CacheTicket $ticket, $data)
		{
			$lifeTime = $ticket->getLifeTime();

			if (is_null($lifeTime))
				$lifeTime = Cache::FOREVER;

			$lifeTime += time();

			Assert::isTrue($lifeTime > time());

			$fileName = $this->compileKey($ticket);

			$this->createPreDirs($fileName);

			file_put_contents($fileName, serialize($data));
			chmod($fileName, self::FILE_PERMISSIONS);

			touch($fileName, $lifeTime);
			$ticket->setExpiredTime($lifeTime);
			$ticket->actual();

			return $this;
		}

		public function compileKey(CacheTicket $ticket)
		{
			$fileName = md5(serialize($ticket->getKey()));

			$resultArray = array(
				$this->getCacheDir(),
				$this->getNamespace(),
				'prefix' => $ticket->getPrefix(),
				$this->compilePreDirs($fileName),
				$fileName
			);

			if (!$resultArray['prefix'])
				unset($resultArray['prefix']);

			return join(DIRECTORY_SEPARATOR, $resultArray);
		}

		/**
		 * @return FileBasedCache
		 */
		public function dropByKey($key)
		{
			if (file_exists($key))
				unlink($key);

			return $this;
		}

		/**
		 * @return FileBasedCache
		 */
		public function clean()
		{
			if(file_exists($this->getCacheDir()))
				Dir::create()->setPath($this->getCacheDir())->delete();

			return $this;
		}

		private function compilePreDirs(
			$fileName,
			$directoryCount = 2,
			$symbolCount = 2
		) {
			$resultArray = array();

			for ($i=0; $i < $directoryCount; $i++ ) {
				$resultArray[] = substr(
					$fileName,
					$i * $symbolCount,
					$symbolCount
				);
			}

			return join(DIRECTORY_SEPARATOR, $resultArray);
		}

		/**
		 * @return FileBasedCache
		 */
		private function createPreDirs($fileName)
		{
			$directory = dirname($fileName);

			if (!file_exists($directory)) {
				$umask = umask(0);
				mkdir($directory, self::DIR_PERMISSIONS, true);
				umask($umask);
			}

			return $this;
		}
	}
?>