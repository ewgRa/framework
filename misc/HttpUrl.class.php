<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class HttpUrl
	{
		private $scheme = null;
		private $host	= null;
		private $path	= null;

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
		public function parse($url)
		{
			$parsed = parse_url($url);

			if (isset($parsed['scheme']))
				$this->setScheme($parsed['scheme']);

			if (isset($parsed['host']))
				$this->setHost($parsed['host']);
			
			if (isset($parsed['path']))
				$this->setPath($parsed['path']);
			
			return $this;
		}
	}
?>