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
	}
?>