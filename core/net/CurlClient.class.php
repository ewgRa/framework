<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class CurlClient
	{
		private $options = array(
			CURLOPT_HEADER => 0,
			CURLOPT_VERBOSE => 0,
			CURLOPT_RETURNTRANSFER => true
		);

		/**
		 * @return CurlClient
		 */
		public static function create(HttpUrl $url, $user = null, $password = null)
		{
			return new self($url, $user, $password);
		}

		/**
		 * @return CurlClient
		 */
		public function __construct(HttpUrl $url, $user = null, $password = null)
		{
			$this->options[CURLOPT_URL] = $url->__toString();

			if ($user) {
				$this->options[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
				$this->options[CURLOPT_USERPWD] = $user.':'.$password;
			}
		}

		public function setOpt($opt, $value)
		{
			$this->options[$opt] = $value;
		}

		public function getResponse(\ewgraFramework\File $toFile = null)
		{
			$ch = curl_init();

			curl_setopt_array($ch, $this->options);
			$fp = null;

			if ($toFile) {
				$fp = fopen($toFile->getPath(), 'w');
				curl_setopt($ch, CURLOPT_FILE, $fp);
			}

			$response = curl_exec($ch);
			curl_close($ch);

			if ($fp)
				fclose($fp);

			return $response;
		}
	}
?>