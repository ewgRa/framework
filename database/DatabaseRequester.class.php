<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class DatabaseRequester
	{
		protected $poolAlias = 'framework';
		
		/**
		 * @return DatabaseRequester
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
		 * @return BaseDatabase
		 */
		public function getPool()
		{
			return Database::me()->getPool($this->getPoolAlias());
		}

		/**
		 * @return BaseDatabase
		 */
		public function db()
		{
			return $this->getPool();
		}
	}
?>