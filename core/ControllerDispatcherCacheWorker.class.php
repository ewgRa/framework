<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class ControllerDispatcherCacheWorker extends ControllerCacheWorker
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
				$this->getController()->
					getRequest()->
					getAttached(AttachedAliases::PAGE);
				
			return $page->getId();
		}
	}
?>