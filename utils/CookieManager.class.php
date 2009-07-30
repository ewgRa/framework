<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class CookieManager extends Singleton
	{
		/**
		 * @return CookieManager
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		/**
		 * @return CookieManager
		 */
		public function setCookie($alias, $value, $expire = null, $path = '/')
		{
			setcookie($alias, $value, $expire, $path);
			return $this;
		}
	}
?>