<?php
	namespace ewgraFramework;
	
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
			// @codeCoverageIgnoreStart
			return parent::getInstance(__CLASS__);
			// @codeCoverageIgnoreEnd
		}
		
		/**
		 * @return CookieManager
		 */
		public function setCookie($alias, $value, $expire = null, $path = '/')
		{
			// @codeCoverageIgnoreStart
			setcookie($alias, $value, $expire, $path);
			return $this;
			// @codeCoverageIgnoreEnd
		}
	}
?>