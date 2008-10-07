<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	interface LocalizerInterface
	{
		public function loadLanguages();
		
		public function setCookieLanguage($languageId, $languageAbbr);
		
		public function defineLanguage();

		public function cutLanguageAbbr();
	}
?>