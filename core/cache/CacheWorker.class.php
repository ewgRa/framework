<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class CacheWorker
	{
		protected $poolAlias = 'framework';

		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return CacheWorker
		 */
		public function setPoolAlias($alias)
		{
			$this->poolAlias = $alias;
			return $this;
		}
		
		public function getPoolAlias()
		{
			return $this->poolAlias;
		}
		
		/**
		 * @return BaseCache
		 */
		public function getPool()
		{
			return Cache::me()->getPool($this->getPoolAlias());
		}

		/**
		 * @return BaseCache
		 */
		public function cache()
		{
			return $this->getPool();
		}
		
		/**
		 * @return CacheTicket
		 */
		public function createTicket()
		{
			$result = null;
			
			if($this->cache()->hasTicketParams($this->getAlias()))
			{
				$result =
					$this->cache()->
						createTicket($this->getAlias())->
						setKey($this->getKey());
			}
			
			return $result;
		}
		
		protected function getKey()
		{
			return null;
		}
		
		protected function getAlias()
		{
			return get_class($this);
		}
	}
?>