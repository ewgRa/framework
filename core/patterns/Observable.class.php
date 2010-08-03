<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class Observable implements ObservableInterface
	{
		private $observers = null;
		
		public function addObserver(Observer $observer, $event)
		{
			if (!isset($this->observers[$event]))
				$this->observers[$event] = array();
				
			$this->observers[$event][] = $observer;
			return $this;
		}
		
		public function removeObserver(Observer $observer, $event)
		{
			throw UnimplementedCodeException::create();
		}
		
		protected function notifyObservers($event, $arguments = array())
		{
			if (!isset($this->observers[$event]))
				return $this;
			
			foreach ($this->observers[$event] as $observer)
				$observer->notify($event, $this, $arguments);
				
			return $this;
		}
	}
?>