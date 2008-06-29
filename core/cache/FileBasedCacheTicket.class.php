<?php
	final class FileBasedCacheTicket extends CacheTicket
	{
		public static function create()
		{
			return new self;
		}
		
		public function storeData()
		{
			$this->getCacheInstance()->set($this);
			return $this;
		}
		
		public function restoreData()
		{
			$this->setData($this->getCacheInstance()->get($this));
			return $this;
		}
	}
?>