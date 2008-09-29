<?php
	/* $Id$ */

	abstract class Cache extends Singleton
	{
		private $isDisabled			= false;
		private $isExpired 			= true;
		private $config				= null;

		/**
		 * @return Cache
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function loadConfig($yamlFile)
		{
			$cacheTicket = $this->createTicket()->
				setPrefix('config')->
				setKey($yamlFile)->
				setActualTime(filemtime($yamlFile))->
				restoreData();

			if($cacheTicket->isExpired())
			{
				$this->config = Yaml::load($yamlFile);
				
				$cacheTicket->
					setData($this->config)->
					setLifeTime(filemtime($yamlFile))->
					storeData();
			}
			else
				$this->config = $cacheTicket->getData();
				
			return $this;
		}
		
		public function getConfig()
		{
			return $this->config;
		}
		
		public function hasTicketParams($ticketAlias)
		{
			return isset($this->config[$ticketAlias]);
		}
		
		public function getTicketParams($ticketAlias)
		{
			if($this->hasTicketParams($ticketAlias))
				return $this->config[$ticketAlias];
			
			return null;
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