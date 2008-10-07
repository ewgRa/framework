<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	abstract class Cache extends Singleton implements CacheInterface
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
		
		/**
		 * @return CacheTicket
		 */
		public function createTicket($ticketAlias = null)
		{
			return CacheTicket::create()->
				setCacheInstance($this)->
				fillParams($this->getTicketParams($ticketAlias));
		}
		
		/**
		 * @return Cache
		 */
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
			return $this->hasTicketParams($ticketAlias)
				? $this->config[$ticketAlias]
				: null;
		}
		
		/**
		 * @return Cache
		 */
		public function disable()
		{
			$this->isDisabled = true;
			return $this;
		}
		
		/**
		 * @return Cache
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
	}
?>