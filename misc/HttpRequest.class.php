<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class HttpRequest
	{
		private $url		= null;
		private $post		= array();
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
		public function setPost(array $vars)
		{
			$this->post = $vars;
			return $this;
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
			return
				$this->hasPostVar($key)
					? $this->post[$key]
					: null;
		}
		
		/**
		 * @return HttpRequest
		 */
		public function setAttached(array $vars)
		{
			$this->attached = $vars;
			return $this;
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
			return
				$this->hasAttachedVar($key)
					? $this->attached[$key]
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
		 * @return HttpRequests
		 */
		public function setUrl(HttpUrl $url)
		{
			$this->url = $url;
			return $this;
		}
	}
?>