<?php
	class UrlHelper extends Singleton
	{
		/**
		 * @return UrlHelper
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public function getEnginePagePath()
		{
			$result = Localizer::me()->getPath();
			
			if(
				Localizer::me()->getSource() == Localizer::SOURCE_LANGUAGE_URL
				|| Localizer::me()->getSource() == Localizer::SOURCE_LANGUAGE_URL_AND_COOKIE
			)
			{
				if(Localizer::me()->getType() == Localizer::DETERMINANT_PATH_BASED)
				{
					$result = Localizer::me()->cutLanguageAbbr();
				}
			}
				
			return $result;
		}
	}