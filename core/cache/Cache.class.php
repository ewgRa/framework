<?php
	class Cache extends Singleton
	{
		private $realization = null;
		
		/**
		 * @return Cache
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function setRealization($realization)
		{
			$this->realization = $realization;
			return $this;
		}
		
		public function getRealization()
		{
			return $this->realization;
		}
		
		public function get($key, $prefix = null, $actualTime = null)
		{
			return $this->getRealization()->getData(
				$key, $prefix, $actualTime
			);
		}
		
		public function set(
			$data, $lifeTillTime = null,
			$key = null, $prefix = null
		)
		{
			return $this->getRealization()->setData(
				$data, $lifeTillTime,
				$key, $prefix
			);
		}

		public function isExpired()
		{
			return $this->getRealization()->isExpired();
		}
	}
?>