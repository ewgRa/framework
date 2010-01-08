<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseCache implements CacheInterface
	{
		private $namespace		= null;
		private $enabled		= true;
		private $ticketAliases 	= array();

		/**
		 * @return BaseCache
		 */
		public function enable()
		{
			$this->enabled = true;
			return $this;
		}
		
		/**
		 * @return BaseCache
		 */
		public function disable()
		{
			$this->enabled = false;
			return $this;
		}
				
		public function isDisabled()
		{
			return !$this->enabled;
		}
		
		/**
		 * @return BaseCache
		 */
		public function setNamespace($namespace)
		{
			$this->namespace = $namespace;
			return $this;
		}
		
		public function getNamespace()
		{
			return $this->namespace;
		}
		
		public function hasTicketParams($ticketAlias)
		{
			return isset($this->ticketAliases[$ticketAlias]);
		}
		
		public function getTicketParams($ticketAlias)
		{
			return
				$this->hasTicketParams($ticketAlias)
					? $this->ticketAliases[$ticketAlias]
					: null;
		}
		
		/**
		 * @return BaseCache
		 */
		public function loadConfig($yamlFile)
		{
			$cacheTicket =
				$this->createTicket()->
				setPrefix('config')->
				setKey($yamlFile)->
				setActualTime(filemtime($yamlFile))->
				restoreData();

			if ($cacheTicket->isExpired()) {
				$yamlConfig = Yaml::load($yamlFile);
				
				if (isset($yamlConfig['ticketAliases']))
					$this->ticketAliases = $yamlConfig['ticketAliases'];
				
				$cacheTicket->
					setData($this->ticketAliases)->
					setLifeTime(filemtime($yamlFile))->
					storeData();
			} else
				$this->ticketAliases = $cacheTicket->getData();
				
			return $this;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function createTicket($ticketAlias = null)
		{
			if (!is_null($ticketAlias) && !$this->getTicketParams($ticketAlias))
				throw MissingArgumentException::create();
			
			$ticketParams = $this->getTicketParams($ticketAlias);
			
			$result =
				CacheTicket::create()->
				setCacheInstance($this)->
				fillParams($ticketParams);
				
			return $result;
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