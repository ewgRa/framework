<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
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