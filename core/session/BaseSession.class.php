<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseSession implements SessionInterface
	{
		private $data = array();

		protected $isStarted = false;

		public function has($alias)
		{
			return isset($this->data[$alias]);
		}
		
		public function getData()
		{
			return $this->data;
		}
		
		/**
		 * @return BaseSession
		 */
		public function setData(array $data)
		{
			$this->data = $data;
			return $this;
		}
		
		public function get($alias)
		{
			$result = null;
			
			if(isset($this->data[$alias]))
				$result = $this->data[$alias];

			return $result;
		}
		
		/**
		 * @return BaseSession
		 */
		public function set($alias, $value)
		{
			$this->data[$alias] = $value;
			return $this;
		}

		/**
		 * @return BaseSession
		 */
		public function drop($alias)
		{
			unset($this->data[$alias]);
			return $this;
		}

		public function isStarted()
		{
			return $this->isStarted;
		}
	}
?>