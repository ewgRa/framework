<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	abstract class CacheWorker
	{
		protected $poolAlias = null;
		
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
			return Cache::me();
		}
		
		/**
		 * @return BaseCache
		 */
		public function cache()
		{
			return $this->getPool();
		}
	}
?>