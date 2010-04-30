<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseDatabaseException extends DefaultException
	{
		/**
		 * @var BaseDatabase
		 */
		private $pool = null;
		
		/**
		 * @return BaseDatabaseException
		 */
		public function setPool(BaseDatabase $pool)
		{
			$this->pool = $pool;
			return $this;
		}
		
		/**
		 * @return BaseDatabase
		 */
		public function getPool()
		{
			return $this->pool;
		}
	}
?>