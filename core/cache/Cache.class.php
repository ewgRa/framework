<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Cache extends Singleton
	{
		const FOREVER = 31536000; // 1 year

		private $pools = array();

		/**
		 * @return Cache
		 * method needed for methods hinting
		 */
		public static function me()
		{
			return parent::me();
		}

		/**
		 * @return Cache
		 */
		public function addPool(CacheInterface $pool, $poolAlias = null)
		{
			$this->pools[$poolAlias] = $pool;
			return $this;
		}

		/**
		 * @return Cache
		 */
		public function swapPools($poolAlias, $swapPoolAlias)
		{
			$pool = $this->getPool($poolAlias);
			$swapPool = $this->getPool($swapPoolAlias);

			$this->pools[$poolAlias] = $swapPool;
			$this->pools[$swapPoolAlias] = $pool;
			return $this;
		}

		public function hasPool($poolAlias)
		{
			return isset($this->pools[$poolAlias]);
		}

		/**
		 * @return CacheInterface
		 */
		public function getPool($poolAlias = null)
		{
			if (!$this->hasPool($poolAlias)) {
				throw
					MissingArgumentException::create(
						'Known nothing about pool "'.$poolAlias.'"'
					);
			}

			return $this->pools[$poolAlias];
		}

		public function getPools()
		{
			return $this->pools;
		}
	}
?>