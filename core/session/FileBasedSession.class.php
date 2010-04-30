<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class FileBasedSession extends BaseSession
	{
		/**
		 * @return FileBasedSession
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return FileBasedSession
		 */
		public function relativeStart()
		{
			if (isset($_REQUEST[session_name()]) && !$this->isStarted())
				$this->start();

			return $this;
		}

		/**
		 * @return FileBasedSession
		 */
		public function start()
		{
			if (!$this->isStarted) {
				$this->isStarted = true;
				session_start();
				$this->setData($_SESSION);
			}
			
			return $this;
		}
		
		/**
		 * @return FileBasedSession
		 */
		public function save()
		{
			if (isset($_SESSION)) {
				foreach ($_SESSION as $k => $v)
					session_unregister($k);
			}
			
			foreach ($this->getData() as $k => $v) {
				session_register($k);
				$_SESSION[$k] = $v;
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
	}
?>