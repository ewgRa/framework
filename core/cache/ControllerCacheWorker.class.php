<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class ControllerCacheWorker extends CacheWorker
	{
		private $controller  = null;

		/**
		 * @return Controller
		 */
		public function setController(Controller $controller)
		{
			$this->controller = $controller;
			return $this;
		}
		
		/**
		 * @return Controller
		 */
		public function getController()
		{
			return $this->controller;
		}
	}
?>