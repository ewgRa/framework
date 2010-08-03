<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface ObserverInterface
	{
		public function notify($event, $observableObject, $arguments = array());
	}
?>