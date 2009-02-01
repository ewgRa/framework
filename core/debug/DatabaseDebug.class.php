<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	final class DatabaseDebug extends BaseDebug
	{
		/**
		 * @var DatabaseDebugDA
		 */
		private $da = null;
		
		/**
		 * @return DatabaseDebug
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return DatabaseDebugDA
		 */
		public function da()
		{
			if(!$this->da)
				$this->da = DatabaseDebugDA::create();
			
			return $this->da;
		}
		
		/**
		 * @return DatabaseDebug
		 */
		public function store()
		{
			foreach($this->getItems() as $item)
				$item->dropTrace();
			
			$itemId =
				$this->da()->insertItem(
					Session::me()->getId(),
					serialize($this->getItems())
				);
			
			return $itemId;
		}
	}
?>