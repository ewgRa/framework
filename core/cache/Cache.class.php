<?php
	/* $Id$ */

	abstract class Cache extends Singleton
	{
		private $isDisabled			= false;
		private $isExpired 			= true;
		
		/**
		 * @return Cache
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public static function factory($realization)
		{
			$reflection = new ReflectionMethod($realization, 'create');

			return
				parent::setInstance(__CLASS__, $reflection->invoke(null));
		}
		
		public function disable()
		{
			$this->isDisabled = true;
			return $this;
		}
		
		public function enable()
		{
			$this->isDisabled = false;
			return $this;
		}
		
		public function isDisabled()
		{
			return $this->isDisabled;
		}
		
		abstract public function get(CacheTicket $ticket);
		
		abstract public function set(CacheTicket $ticket);
		
		abstract public function createTicket();
	}
?>