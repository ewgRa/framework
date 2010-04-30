<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class HttpUrl
	{
		private $scheme = null;
		private $host	= null;
		private $path	= null;
		private $query	= null;
		
		/**
		 * @return HttpUrl
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return HttpUrl
		 */
		public static function createFromString($string)
		{
			return self::create()->parse($string);
		}
		
		/**
		 * @return HttpUrl
		 */
		public function setScheme($scheme)
		{
			$this->scheme = $scheme;
			return $this;
		}

		public function getScheme()
		{
			return $this->scheme;
		}
		
		/**
		 * @return HttpUrl
		 */
		public function setHost($host)
		{
			$this->host = $host;
			return $this;
		}

		public function getHost()
		{
			return $this->host;
		}
		
		/**
		 * @return HttpUrl
		 */
		public function setPath($path)
		{
			$this->path = $path;
			return $this;
		}

		public function getPath()
		{
			return $this->path;
		}
		
		/**
		 * @return HttpUrl
		 */
		public function setQuery($query)
		{
			$this->query = $query;
			return $this;
		}

		public function getQuery()
		{
			return $this->query;
		}
		
		public function __toString()
		{
			return
				($this->getScheme() ? $this->getScheme().'://' : null)
				.($this->getHost() ? $this->getHost() : null)
				.$this->getPath()
				.($this->getQuery() ? '?'.$this->getQuery() : null);
		}

		/**
		 * @return HttpUrl
		 */
		private function parse($url)
		{
			$parsed = parse_url($url);

			if (isset($parsed['scheme']))
				$this->setScheme($parsed['scheme']);

			if (isset($parsed['host']))
				$this->setHost($parsed['host']);
			
			if (isset($parsed['path']))
				$this->setPath($parsed['path']);
			
			if (isset($parsed['query']))
				$this->setQuery($parsed['query']);
			
			return $this;
		}
	}
?>