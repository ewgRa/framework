<?php
	final class LocalizerPathBased extends Localizer
	{
		protected $path = null;
		protected $type = self::DETERMINANT_PATH_BASED;
		
		/**
		 * @return LocalizerPathUrl
		 */
		public static function create()
		{
			return new self;
		}
		
		public function getPath()
		{
			return $this->path;
		}
		
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
			{
				$result = $parts[1];
			}
			
			return $result;
		}
		
		public function cutLanguageAbbr()
		{
			$result = $this->getPath();
			
			if($this->getDefinedLanguageAbbr() == $this->getLanguageAbbr())
			{
				// FIXME: use substr
				$result = preg_replace(
					'@/' . preg_quote($this->getLanguageAbbr(), '@') . '@',
					'',
					$this->getPath(),
					1
				);
			}
			
			return $result;
		}
	}
?>
