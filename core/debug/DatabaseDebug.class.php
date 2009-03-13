<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
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