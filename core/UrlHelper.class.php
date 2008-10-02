<?php
	/* $Id$ */
	
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
			return
				$this->isLanguageInUrl()
					? Localizer::me()->cutLanguageAbbr()
					: Localizer::me()->getPath();
		}
		
		public function isLanguageInUrl()
		{
			return
				in_array(
					Localizer::me()->getSource(),
					array(
						Localizer::SOURCE_LANGUAGE_URL,
						Localizer::SOURCE_LANGUAGE_URL_AND_COOKIE
					)
				)
				&& Localizer::me()->getType() == Localizer::DETERMINANT_PATH_BASED;
		}
	}
?>