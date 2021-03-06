<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class HttpRequest
	{
		/**
		 * @var HttpUrl
		 */
		private $url		= null;

		private $get		= array();
		private $post		= array();
		private $cookie		= array();
		private $server		= array();
		private $files		= array();
		private $attached	= array();

		/**
		 * @return HttpRequest
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return HttpRequest
		 */
		public function setGet(array $vars)
		{
			$this->get = $vars;
			return $this;
		}

		/**
		 * @return array
		 */
		public function getGet()
		{
			return $this->get;
		}

		public function hasGet()
		{
			return count($this->get) !== 0;
		}

		/**
		 * @return HttpRequest
		 */
		public function setGetVar($key, $value)
		{
			$this->get[$key] = $value;
			return $this;
		}

		public function hasGetVar($key)
		{
			return isset($this->get[$key]);
		}

		public function getGetVar($key)
		{
			if (!$this->hasGetVar($key)) {
				throw MissingArgumentException::create(
					'known nothing about key "'.$key.'"'
				);
			}

			return $this->get[$key];
		}

		/**
		 * @return HttpRequest
		 */
		public function setPost(array $vars)
		{
			$this->post = $vars;
			return $this;
		}

		/**
		 * @return array
		 */
		public function getPost()
		{
			return $this->post;
		}

		public function hasPost()
		{
			return count($this->post) !== 0;
		}

		/**
		 * @return HttpRequest
		 */
		public function setPostVar($key, $value)
		{
			$this->post[$key] = $value;
			return $this;
		}

		public function hasPostVar($key)
		{
			return isset($this->post[$key]);
		}

		public function getPostVar($key)
		{
			if (!$this->hasPostVar($key)) {
				throw MissingArgumentException::create(
					'known nothing about key "'.$key.'"'
				);
			}

			return $this->post[$key];
		}

		/**
		 * @return HttpRequest
		 */
		public function setAttached(array $vars)
		{
			$this->attached = $vars;
			return $this;
		}

		/**
		 * @return array
		 */
		public function getAttached()
		{
			return $this->attached;
		}

		public function hasAttached()
		{
			return count($this->attached) !== 0;
		}

		/**
		 * @return HttpRequest
		 */
		public function setAttachedVar($key, $value)
		{
			$this->attached[$key] = $value;
			return $this;
		}

		public function hasAttachedVar($key)
		{
			return isset($this->attached[$key]);
		}

		public function getAttachedVar($key)
		{
			if (!$this->hasAttachedVar($key)) {
				throw MissingArgumentException::create(
					'known nothing about key "'.$key.'"'
				);
			}

			return $this->attached[$key];
		}

		/**
		 * @return HttpRequest
		 */
		public function setCookie(array $vars)
		{
			$this->cookie = $vars;
			return $this;
		}

		/**
		 * @return array
		 */
		public function getCookie()
		{
			return $this->cookie;
		}

		public function hasCookie()
		{
			return count($this->cookie) !== 0;
		}

		/**
		 * @return HttpRequest
		 */
		public function setCookieVar($key, $value)
		{
			$this->cookie[$key] = $value;
			return $this;
		}

		public function hasCookieVar($key)
		{
			return isset($this->cookie[$key]);
		}

		public function getCookieVar($key)
		{
			if (!$this->hasCookieVar($key)) {
				throw MissingArgumentException::create(
					'known nothing about key "'.$key.'"'
				);
			}

			return $this->cookie[$key];
		}

		/**
		 * @return array
		 */
		public function getServer()
		{
			return $this->server;
		}

		public function hasServer()
		{
			return count($this->server) !== 0;
		}

		/**
		 * @return HttpRequest
		 */
		public function setServer(array $server)
		{
			$this->server = $server;
			return $this;
		}

		/**
		 * @return HttpRequest
		 */
		public function setServerVar($key, $value)
		{
			$this->server[$key] = $value;
			return $this;
		}

		public function hasServerVar($key)
		{
			return isset($this->server[$key]);
		}

		public function getServerVar($key)
		{
			if (!$this->hasServerVar($key)) {
				throw MissingArgumentException::create(
					'known nothing about key "'.$key.'"'
				);
			}

			return $this->server[$key];
		}

		/**
		 * @return array
		 */
		public function getFiles()
		{
			return $this->files;
		}

		public function hasFiles()
		{
			return count($this->files) !== 0;
		}

		/**
		 * @return HttpRequest
		 */
		public function setFiles(array $files)
		{
			$this->files = $files;
			return $this;
		}

		/**
		 * @return HttpRequest
		 */
		public function setFilesVar($key, $value)
		{
			$this->files[$key] = $value;
			return $this;
		}

		public function hasFilesVar($key)
		{
			return isset($this->files[$key]);
		}

		public function getFilesVar($key)
		{
			if (!$this->hasFilesVar($key)) {
				throw MissingArgumentException::create(
					'known nothing about key "'.$key.'"'
				);
			}

			return $this->files[$key];
		}

		public function hasHttpReferer()
		{
			return $this->hasServerVar('HTTP_REFERER');
		}

		public function getHttpReferer()
		{
			return $this->getServerVar('HTTP_REFERER');
		}

		public function getRemoteIp()
		{
			return
				$this->hasServerVar('REMOTE_ADDR')
					? $this->getServerVar('REMOTE_ADDR')
					: null;
		}

		public function getUserAgent()
		{
			return
				$this->hasServerVar('HTTP_USER_AGENT')
					? $this->getServerVar('HTTP_USER_AGENT')
					: null;
		}

		/**
		 * @return HttpUrl
		 */
		public function getUrl()
		{
			return $this->url;
		}

		/**
		 * @return HttpRequest
		 */
		public function setUrl(HttpUrl $url)
		{
			$this->url = $url;
			return $this;
		}
	}
?>