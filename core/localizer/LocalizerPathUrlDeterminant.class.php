<?php
	class LocalizerPathUrlDeterminant
	{
		const TYPE = Localizer::DETERMINANT_PATH_BASED;
		
		private $url = null;
		
		/**
		 * @return LocalizerPathUrlDeterminant
		 */
		public static function create()
		{
			return new self;
		}
		
		public function getType()
		{
			return self::TYPE;
		}
		
		public function getDefinedLanguageAbbr()
		{
			$result = null;
			
			$urlParts = parse_url($this->getUrl());
			$parts = explode('/', $urlParts['path']);

			if(count($parts) > 2)
			{
				$result = $parts[1];
			}
			
			return $result;
		}
		
		public function getUrl()
		{
			return $this->url;
		}
		
		public function setUrl($url)
		{
			$this->url = $url;
			return $this;
		}
		
		public function cutLanguageAbbr($languageAbbr)
		{
			$result = $this->getUrl();
			
			if($this->getDefinedLanguageAbbr() == $languageAbbr)
			{
				$result = preg_replace(
					'@/' . preg_quote($languageAbbr, '@') . '@',
					'',
					$this->getUrl(),
					1
				);
			}
			
			return $result;
		}
	}
?>
