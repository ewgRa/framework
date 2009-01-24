<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	interface LocalizerInterface
	{
		public static function create();
		
		/**
		 * @return Localizer
		 */
		public function selectDefaultLanguage($languageAbbr);
		
		/**
		 * @return Localizer
		 */
		public function setCookieLanguage(Language $language);
		
		/**
		 * @return Localizer
		 */
		public function defineLanguage(HttpUrl $url);

		/**
		 * @return Language
		 */
		public function getRequestLanguage();
		
		/**
		 * @return HttpUrl
		 */
		public function removeLanguageFromUrl(HttpUrl $url);
	}
?>