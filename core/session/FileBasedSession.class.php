<?php
	// FIXME: tested?
	class FileBasedSession
	{
		private $data = array();
		private $isStarted = false;

		/**
		 * @return FileBasedSession
		 */
		public static function create()
		{
			return new self;
		}
		
		public function relativeStart()
		{
			if(isset($_REQUEST[session_name()]) && !$this->isStarted())
				$this->start();

			return $this;
		}

		public function start()
		{
			if(!$this->started)
			{
				$this->isStarted = true;
				session_start();
				$this->data = $_SESSION;
			}
			
			return $this;
		}

		public function isStarted()
		{
			return $this->isStarted;
		}

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
			$result = null;
			
			if($this->isStarted())
				$result = session_id();
			
			return $result;
		}
		
		public function get($alias)
		{
			$result = null;
			
			if(isset($this->data[$alias]))
				$result = $this->data[$alias];

			return $result;
		}
		
		public function set($alias, $value)
		{
			$this->data[$alias] = $value;
			return $this;
		}
	}
?>