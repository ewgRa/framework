<?php
	/* $Id$ */

	$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'CacheInterface.class.php';
	
	if(!interface_exists('CacheInterface', false) && file_exists($file))
		require_once($file);
	
	$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'CacheTicket.class.php';
		
	if(!class_exists('CacheTicket', false) && file_exists($file))
		require_once($file);
	
	$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . '../Assert.class.php';
		
	if(!class_exists('Assert', false) && file_exists($file))
		require_once($file);
		
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseCache implements CacheInterface
	{
		private $isDisabled	= false;

		/**
		 * @return BaseCache
		 */
		public function disable()
		{
			$this->isDisabled = true;
			return $this;
		}
		
		/**
		 * @return BaseCache
		 */
		public function enable()
		{
			$this->isDisabled = false;
			return $this;
		}
		
		public function isDisabled()
		{
			return $this->isDisabled;
		}
		
		protected function debug(CacheTicket $ticket)
		{
			$debugItem =
				DebugItem::create()->
					setType(DebugItem::CACHE)->
					setData(clone $ticket);
			
			Debug::me()->addItem($debugItem);
			
			return $this;
		}
	}
?>