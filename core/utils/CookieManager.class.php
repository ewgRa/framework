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
		 * method needed for methods hinting
		 */
		public static function me()
		{
			// @codeCoverageIgnoreStart
			return parent::me();
			// @codeCoverageIgnoreEnd
		}

		/**
		 * @return CookieManager
		 */
		public function set($alias, $value, $expire = null, $path = '/')
		{
			// @codeCoverageIgnoreStart
			setcookie($alias, $value, $expire, $path);
			return $this;
			// @codeCoverageIgnoreEnd
		}

		public function has($alias)
		{
			return isset($_COOKIE[$alias]);
		}

		public function get($alias)
		{
			return $_COOKIE[$alias];
		}
	}
?>