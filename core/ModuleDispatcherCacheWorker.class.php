<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class ModuleDispatcherCacheWorker extends ModuleCacheWorker
	{
		/**
		 * @return ContentCacheWorker
		 */
		public static function create()
		{
			return new self;
		}
		
		protected function getAlias()
		{
			return __CLASS__;
		}
		
		protected function getKey()
		{
			$page =
				$this->getModule()->
					getRequest()->
					getAttached(AttachedAliases::PAGE);
				
			return $page->getId();
		}
	}
?>