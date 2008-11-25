<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	class Database extends Singleton
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