<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class FtpClient
	{
		private $resource = null;

		private $host = null;
		private $user = null;
		private $password = null;

		/**
		 * @return FtpClient
		 */
		public static function create($host, $user, $password)
		{
			return new self($host, $user, $password);
		}

		/**
		 * @return FtpClient
		 */
		public function __construct($host, $user, $password)
		{
			$this->host = $host;
			$this->user = $user;
			$this->password = $password;
		}

		/**
		 * @return FtpClient
		 */
		public function connect()
		{
			Assert::isFalse(
				$this->isConnected(),
				'Close previous connection before connect'
			);

			$this->resource = ftp_connect($this->host);

			if ($this->resource === false)
				throw ConnectException::create();

			if (!ftp_login($this->resource, $this->user, $this->password))
				throw ConnectException::create();

			return $this;
		}

		public function isConnected()
		{
			return !is_null($this->resource);
		}

		/**
		 * @return FtpClient
		 */
		public function disconnect()
		{
			ftp_close($this->resource);
			$this->resource = null;
			return $this;
		}

		/**
		 * @return FtpClient
		 */
		public function setPassiveMode($passiveMode = true)
		{
			ftp_pasv($this->resource, $passiveMode);
			return $this;
		}

		/**
		 * @return array
		 */
		public function getFileList($directory = '.')
		{
			return ftp_nlist($this->resource, $directory);
		}

		/**
		 * @return FtpClient
		 */
		public function downloadFile($file, File $distination)
		{
			ftp_get($this->resource, $distination->getPath(), $file, FTP_BINARY);

			return $this;
		}

		/**
		 * @return FtpClient
		 */
		public function changeDirectory($directory)
		{
			ftp_chdir($this->resource, $directory);

			return $this;
		}
	}
?>