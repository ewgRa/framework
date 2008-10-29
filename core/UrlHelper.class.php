<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
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
		
		public function getLocalizerPath()
		{
			$result = '';
			
			if(
				$this->isLanguageInUrl()
				&& Localizer::me()->getSource() != BaseLocalizer::SOURCE_LANGUAGE_URL_AND_COOKIE
			) {
				$result = '/' . Localizer::me()->getRequestLanguage()->getAbbr();
			}
			
			return $result;
		}
		
		public function isLanguageInUrl()
		{
			return
				in_array(
					Localizer::me()->getSource(),
					array(
						BaseLocalizer::SOURCE_LANGUAGE_URL,
						BaseLocalizer::SOURCE_LANGUAGE_URL_AND_COOKIE
					)
				)
				&& Localizer::me()->getType() == BaseLocalizer::DETERMINANT_PATH_BASED;
		}
	}
?>