<?php
	// tested?
	class UrlHelper extends Singleton
	{
		private $basedUrl = null;
		
		/**
		 * @return UrlHelper
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public function setBasedUrl($url)
		{
			$this->basedUrl = $url;
			return $this;
		}
		
		public function getBasedUrl()
		{
			return $this->basedUrl;
		}
		
		public function getEnginePageUrl()
		{
			$localizerDeterminant = Localizer::me()->getDeterminantRealization();
			$result = $localizerDeterminant->getUrl();
			
			if(
				Localizer::me()->getSource() == Localizer::SOURCE_LANGUAGE_URL
				|| Localizer::me()->getSource() == Localizer::SOURCE_LANGUAGE_URL_AND_COOKIE
			)
			{
				if($localizerDeterminant->getType() == Localizer::DETERMINANT_PATH_BASED)
				{
					$result = $localizerDeterminant->cutLanguageAbbr(
						Localizer::me()->getLanguageAbbr()
					);
				}
			}
				
			return $result;
		}

		public function getBaseUrl()
		{
			return $this->getBasedUrl();
		}
	}