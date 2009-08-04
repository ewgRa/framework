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
		private $enabled	= false;
		private $ticketAliases = array();

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
		
		public function hasTicketParams($ticketAlias)
		{
			return isset($this->ticketAliases[$ticketAlias]);
		}
		
		public function getTicketParams($ticketAlias)
		{
			return $this->hasTicketParams($ticketAlias)
				? $this->ticketAliases[$ticketAlias]
				: null;
		}
		
		/**
		 * @return BaseCache
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
				$yamlConfig = Yaml::load($yamlFile);
				
				if(isset($yamlConfig['ticketAliases']))
				{
					foreach($yamlConfig['ticketAliases'] as $alias => $settings)
						$this->ticketAliases[$alias] = $settings;
				}
				
				$cacheTicket->
					setData(
						array('ticketAliases' => $this->ticketAliases)
					)->
					setLifeTime(filemtime($yamlFile))->
					storeData();
			}
			else
			{
				$data = $cacheTicket->getData();
				$this->ticketAliases = $data['ticketAliases'];
			}
				
			return $this;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function createTicket($ticketAlias = null)
		{
			if(!is_null($ticketAlias) && !$this->getTicketParams($ticketAlias))
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