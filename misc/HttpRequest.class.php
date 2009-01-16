<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * // FIXME:tested?
	*/
	final class HttpRequest
	{
		private $post = array();
		private $attached = array();
		
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
		public function setPostArray(array $vars)
		{
			$this->post = $vars;
			return $this;
		}
		
		public function hasPostArray()
		{
			return count($this->post) !== 0;
		}
		
		/**
		 * @return HttpRequest
		 */
		public function setPost($key, $value)
		{
			$this->post[$key] = $value;
			return $this;
		}
		
		public function hasPost($key)
		{
			return isset($this->post[$key]);
		}
		
		public function getPost($key)
		{
			return
				$this->hasPost($key)
					? $this->post[$key]
					: null;
		}
		
		/**
		 * @return HttpRequest
		 */
		public function setAttachedArray(array $vars)
		{
			$this->attached = $vars;
			return $this;
		}
		
		public function hasAttachedArray()
		{
			return count($this->attached) !== 0;
		}
		
		/**
		 * @return HttpRequest
		 */
		public function setAttached($key, $value)
		{
			$this->attached[$key] = $value;
			return $this;
		}
		
		public function hasAttached($key)
		{
			return isset($this->attached[$key]);
		}
		
		public function getAttached($key)
		{
			return
				$this->hasAttached($key)
					? $this->attached[$key]
					: null;
		}
	}
?>