<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class HttpRequest
	{
		private $url		= null;
		private $get		= array();
		private $post		= array();
		private $cookie		= array();
		private $server		= array();
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
					'known nothing about key "' . $key . '"'
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
					'known nothing about key "' . $key . '"'
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
					'known nothing about key "' . $key . '"'
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
					'known nothing about key "' . $key . '"'
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
					'known nothing about key "' . $key . '"'
				);
			}
			
			return $this->server[$key];
		}
		
		public function getHttpReferer()
		{
			return $this->getServerVar('HTTP_REFERER');
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