<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class ModuleCacheWorker extends CacheWorker
	{
		private $module  = null;

		/**
		 * @return Module
		 */
		public function setModule(Module $module)
		{
			$this->module = $module;
			return $this;
		}
		
		/**
		 * @return Module
		 */
		public function getModule()
		{
			return $this->module;
		}
	}
?>