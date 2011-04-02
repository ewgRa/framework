<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface ObservableInterface
	{
		/**
		 * @return unique string $hash
		 */
		public function addObserver($event, $callback);

		public function hasObserver($hash);
		public function removeObserver($hash);
	}
?>