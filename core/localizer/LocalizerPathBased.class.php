<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	final class LocalizerPathBased extends BaseLocalizer
	{
		protected $path = null;
		protected $type = self::DETERMINANT_PATH_BASED;
		
		/**
		 * @return LocalizerPathBased
		 */
		public static function create()
		{
			return new self;
		}
		
		public function getPath()
		{
			return $this->path;
		}
		
		/**
		 * @return LocalizerPathBased
		 */
		public function setPath($path)
		{
			$this->path = $path;
			return $this;
		}
		
		public function getDefinedLanguageAbbr()
		{
			$result = null;
			
			$parts = explode('/', $this->getPath());

			if(count($parts) > 2)
				$result = $parts[1];
			
			return $result;
		}
		
		public function cutLanguageAbbr()
		{
			$result = $this->getPath();
			
			if(
				$this->getDefinedLanguageAbbr()
					== $this->getRequestLanguage()->getAbbr()
			)
				$result = substr(
					$result,
					strlen($this->getRequestLanguage()->getAbbr()) + 1
				);
			
			return $result;
		}
	}
?>