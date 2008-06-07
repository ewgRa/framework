<?php
	// FIXME: testing?
	class LocalizerPathUrlDeterminant
	{
		const TYPE = Localizer::DETERMINANT_PATH_BASED;
		
		private $url = null;
		
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
			$parts = explode('/', $this->getUrl());

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
			return substr($this->getUrl(), strlen('/' . $languageAbbr));
		}
	}
?>
