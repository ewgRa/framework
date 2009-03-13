<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Database extends Singleton
	{
		private $pools = array();
		
		/**
		 * @return BaseDatabase
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function addPool(BaseDatabase $pool, $poolAlias = null)
		{
			$this->pools[$poolAlias] = $pool;
			return $this;
		}
		
		public function hasPool($poolAlias)
		{
			return isset($this->pools[$poolAlias]);
		}
		
		/**
		 * @return BaseDatabase
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
	}
?>