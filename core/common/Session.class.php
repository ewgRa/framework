<?php
	namespace ewgraFramework;

	// @codeCoverageIgnoreStart

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Session extends Singleton
	{
		private $isStarted = false;

		/**
		 * @return Session
		 * method needed for methods hinting
		 */
		public static function me()
		{
			return parent::me();
		}

		public function isStarted()
		{
			return $this->isStarted;
		}

		/**
		 * @return Session
		 */
		public function relativeStart()
		{
			if (
				(
					// FIXME: use CookieManager
					isset($_COOKIE[session_name()])
					|| isset($_GET[session_name()])
					|| isset($_POST[session_name()])
				)
				&& !$this->isStarted()
			)
				$this->start();

			return $this;
		}

		/**
		 * @return Session
		 */
		public function start()
		{
			if (!$this->isStarted()) {
				$this->isStarted = true;
				session_start();
			}

			return $this;
		}

		/**
		 * @return Session
		 */
		public function destroy()
		{
			if ($this->isStarted()) {
				$this->isStarted = false;
				session_destroy();
				unset($_SESSION);
			}

			return $this;
		}

		/**
		 * @return Session
		 */
		public function startAsNew()
		{
			if ($this->isStarted()) {
				session_regenerate_id();
				$this->clean();
			}

			return $this;
		}

		public function getId()
		{
			return
				$this->isStarted()
					? session_id()
					: null;
		}

		public function has($key)
		{
			return isset($_SESSION[$key]);
		}

		public function get($key)
		{
			if (!$this->has($key))
				throw MissingArgumentException::create('known nothing about key');

			return $_SESSION[$key];
		}

		/**
		 * @return Session
		 */
		public function set($key, $value)
		{
			$_SESSION[$key] = $value;
			return $this;
		}

		/**
		 * @return Session
		 */
		public function drop($key)
		{
			unset($_SESSION[$key]);
			return $this;
		}

		/**
		 * @return Session
		 */
		public function clean()
		{
			$_SESSION = array();
			return $this;
		}
	}

	// @codeCoverageIgnoreEnd
?>