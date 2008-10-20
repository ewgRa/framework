<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	abstract class BaseSession implements BaseSessionInterface
	{
		protected $isStarted = false;

		/**
		 * @return BaseSession
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

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