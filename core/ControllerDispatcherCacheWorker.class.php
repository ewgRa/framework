<?php
	/* $Id$ */

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