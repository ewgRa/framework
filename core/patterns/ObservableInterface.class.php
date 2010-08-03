<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface ObservableInterface
	{
		public function addObserver(ObserverInterface $observer, $event);
		public function removeObserver(ObserverInterface $observer, $event);

		protected function notifyObservers($event, $arguments = array());
	}
?>