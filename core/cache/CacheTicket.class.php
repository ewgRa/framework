<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	final class CacheTicket
	{
		/**
		 * @var BaseCache
		 */
		private $cacheInstance = null;

		private $data 		= null;
		private $prefix 	= null;
		private $key 		= null;
		private $actualTime = null;
		private $isExpired	= true;
		private $lifeTime 	= null;
		
		/**
		 * @return CacheTicket
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function __construct()
		{
			$this->setLifeTime(31536000 + time()); # one year
			return $this;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function setCacheInstance(BaseCache $instance)
		{
			$this->cacheInstance = $instance;
			return $this;
		}
		
		/**
		 * @return Cache
		 */
		public function getCacheInstance()
		{
			return $this->cacheInstance;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function fillParams(array $params = null)
		{
			if($params)
			{
				if(isset($params['prefix']))
					$this->setPrefix($params['prefix']);

				if(isset($params['lifeTime']))
					$this->setLifeTime(time() + $params['lifeTime']);
			}
			
			return $this;
		}
		
		public function getPrefix()
		{
			return $this->prefix;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function setPrefix($prefix)
		{
			$this->prefix = $prefix;
			return $this;
		}
		
		public function getKey()
		{
			return $this->key;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function setKey()
		{
			$this->key = func_get_args();
			return $this;
		}
		
		public function getActualTime()
		{
			return $this->actualTime;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function setActualTime($actualTime)
		{
			$this->actualTime = $actualTime;
			return $this;
		}
		
		public function isExpired()
		{
			return $this->isExpired;
		}

		public function getLifeTime()
		{
			return $this->lifeTime;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function setLifeTime($lifeTime)
		{
			$this->lifeTime = $lifeTime;
			return $this;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function expired()
		{
			$this->isExpired = true;
			return $this;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function actual()
		{
			$this->isExpired = false;
			return $this;
		}
		
		public function getData()
		{
			return $this->data;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function setData($data)
		{
			$this->data = $data;
			return $this;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function storeData()
		{
			$this->getCacheInstance()->set($this);
			return $this;
		}

		/**
		 * @return CacheTicket
		 */
		public function restoreData()
		{
			$this->setData(
				$this->getCacheInstance()->get($this)
			);
			
			return $this;
		}
	}
?>