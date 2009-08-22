<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Cache extends Singleton
	{
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
			if (!$this->hasPool($poolAlias)) {
				throw
					MissingArgumentException::create(
						'Known nothing about pool ' . $poolAlias
					);
			}

			return $this->pools[$poolAlias];
		}
	}
?>