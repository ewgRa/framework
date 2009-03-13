<?php
	/* $Id$ */

	$file = join(
		DIRECTORY_SEPARATOR,
		array(
			dirname(__FILE__), '..' , '..' , 'patterns' , 'Singleton.class.php'
		)
	);
	
	if(!class_exists('Singleton', false) && file_exists($file))
		require_once($file);
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Cache extends Singleton
	{
		private $config		= null;

		/**
		 * @return Cache
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		/**
		 * @return Cache
		 */
		public function addPool(BaseCache $pool, $poolAlias = null)
		{
			$this->pools[$poolAlias] = $pool;
			return $this;
		}
		
		public function hasPool($poolAlias)
		{
			return isset($this->pools[$poolAlias]);
		}
		
		/**
		 * @return BaseCache
		 */
		public function getPool($poolAlias = null)
		{
			if($this->hasPool($poolAlias))
				return $this->pools[$poolAlias];
			else
				throw
					ExceptionsMapper::me()->createException('MissingArgument')->
						setMessage('Known nothing about pool ' . $poolAlias);
		}

		/**
		 * @return CacheTicket
		 */
		public function createTicket($ticketAlias = null, $pool = null)
		{
			if(!is_null($ticketAlias) && !$this->getTicketParams($ticketAlias))
				throw ExceptionsMapper::me()->createException('MissingArgument');
			
			if(!is_null($pool) && !$this->hasPool($pool))
				throw ExceptionsMapper::me()->createException('MissingArgument');
			
			$ticketParams = $this->getTicketParams($ticketAlias);
			
			if(!$pool && isset($ticketParams['pool']))
				$pool = $ticketParams['pool'];
			
			$result =
				CacheTicket::create()->
					setCacheInstance($this->getPool($pool))->
					fillParams($ticketParams);
				
			return $result;
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
	}
?>