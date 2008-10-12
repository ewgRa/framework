<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	final class FileBasedSession extends BaseSession
	{
		private $data = array();

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
			if(isset($_REQUEST[session_name()]) && !$this->isStarted())
				$this->start();

			return $this;
		}

		/**
		 * @return FileBasedSession
		 */
		public function start()
		{
			if(!$this->isStarted)
			{
				$this->isStarted = true;
				session_start();
				$this->data = $_SESSION;
			}
			
			return $this;
		}
		
		/**
		 * @return FileBasedSession
		 */
		public function save()
		{
			if(isset($_SESSION))
			{
				foreach($_SESSION as $k => $v)
					session_unregister($k);
			}
			
			foreach($this->data as $k => $v)
			{
				session_register($k);
				$_SESSION[$k] = $v;
			}
			
			return $this;
		}

		public function getId()
		{
			return $this->isStarted()
				? session_id()
				: null;
		}
		
		public function get($alias)
		{
			$result = null;
			
			if(isset($this->data[$alias]))
				$result = $this->data[$alias];

			return $result;
		}
		
		/**
		 * @return FileBasedSession
		 */
		public function set($alias, $value)
		{
			$this->data[$alias] = $value;
			return $this;
		}

		/**
		 * @return FileBasedSession
		 */
		public function drop($alias)
		{
			unset($this->data[$alias]);
			return $this;
		}
	}
?>