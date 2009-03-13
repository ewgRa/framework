<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseSession implements SessionInterface
	{
		protected $isStarted = false;

		public function getCookie($alias)
		{
			return
				isset($_COOKIE[$alias])
					? $_COOKIE[$alias]
					: null;
		}

		public function setCookie($alias, $value, $expire = null, $path = '/')
		{
			setcookie($alias, $value, $expire, $path);
			return $this;
		}
		
		public function isStarted()
		{
			return $this->isStarted;
		}
	}
?>