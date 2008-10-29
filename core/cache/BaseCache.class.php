<?php
	/* $Id$ */

	$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'BaseCacheInterface.class.php';
	
	if(!interface_exists('BaseCacheInterface', false) && file_exists($file))
		require_once($file);
	
	$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'CacheTicket.class.php';
		
	if(!class_exists('CacheTicket', false) && file_exists($file))
		require_once($file);
	
	$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . '../Assert.class.php';
		
	if(!class_exists('Assert', false) && file_exists($file))
		require_once($file);
		
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	abstract class BaseCache implements BaseCacheInterface
	{
		private $isDisabled	= false;
		private $isExpired 	= true;
		private $config		= null;

		/**
		 * @return CacheTicket
		 */
		public function createTicket($ticketAlias = null)
		{
			if(!is_null($ticketAlias) && !$this->getTicketParams($ticketAlias))
				throw new MissingArgumentException();
			
			$result = CacheTicket::create()->
				setCacheInstance($this)->
				fillParams($this->getTicketParams($ticketAlias));
				
			return $result;
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
	}
?>