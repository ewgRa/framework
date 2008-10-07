<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	abstract class Localizer extends Singleton implements LocalizerInterface
	{
		const SOURCE_LANGUAGE_DEFAULT 		 = 1;
		const SOURCE_LANGUAGE_COOKIE  		 = 2;
		const SOURCE_LANGUAGE_URL 	  		 = 3;
		const SOURCE_LANGUAGE_URL_AND_COOKIE = 4;
		
		const DETERMINANT_PATH_BASED = 5;
		const DETERMINANT_HOST_BASED = 6;
		
		private $languageAbbr 		= null;
		private $languageId 		= null;
		private $languages 			= null;
		private $cookieLanguageId 	= null;
		private $cookieLanguageAbbr = null;
		private $source 			= null;
		
		protected $type = null;

		/**
		 * @return Localizer
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function getType()
		{
			return $this->type;
		}
		
		public function getLanguageAbbr()
		{
			return $this->languageAbbr;
		}
		
		/**
		 * @return Localizer
		 */
		public function setLanguageAbbr($abbr)
		{
			$this->languageAbbr = $abbr;
			return $this;
		}

		public function getSource()
		{
			return $this->source;
		}
		
		/**
		 * @return Localizer
		 */
		public function setSource($source)
		{
			$this->source = $source;
			return $this;
		}
		
		public function getLanguageId()
		{
			return $this->languageId;
		}
		
		/**
		 * @return Localizer
		 */
		public function setLanguageId($id)
		{
			$this->languageId = $id;
			return $this;
		}

		public function getLanguages()
		{
			return $this->languages;
		}
		
		/**
		 * @return Localizer
		 */
		public function setCookieLanguage($languageId, $languageAbbr)
		{
			$this->cookieLanguageId = $languageId;
			$this->cookieLanguageAbbr = $languageAbbr;
			return $this;
		}
		
		/**
		 * @return Localizer
		 */
		public function defineLanguage()
		{
			if($this->cookieLanguageId && $this->cookieLanguageAbbr)
			{
				$this->setLanguageId($this->cookieLanguageId)->
					setLanguageAbbr($this->cookieLanguageAbbr);
					
				$this->setSource(self::SOURCE_LANGUAGE_COOKIE);
			}

			$probableLanguageAbbr = $this->getDefinedLanguageAbbr();

			if($this->languages && in_array($probableLanguageAbbr, $this->languages))
			{
				$this->setLanguageAbbr($probableLanguageAbbr);
				$flipLanguages = array_flip($this->languages);
				$this->setLanguageId($flipLanguages[$probableLanguageAbbr]);

				$this->setSource(
					$this->getSource() == self::SOURCE_LANGUAGE_COOKIE
						? self::SOURCE_LANGUAGE_URL_AND_COOKIE
						: self::SOURCE_LANGUAGE_URL
				);
			}

			return $this;
		}

		/**
		 * @return Localizer
		 */
		public function loadLanguages()
		{
			$dbQuery = "SELECT * FROM " . Database::me()->getTable('Languages');
			$this->languages = array();
			$dbResult = Database::me()->query($dbQuery);

			while($dbRow = Database::me()->fetchArray($dbResult))
				$this->languages[$dbRow['id']] = $dbRow['abbr'];
			
			return $this;
		}

		/**
		 * @return Localizer
		 * // FIXME: we already have languages in LoadLanguages, no needed
		 * 			execute query
		 */
		public function selectDefaultLanguage()
		{
			$dbQuery = "SELECT * FROM " . Database::me()->getTable('Languages')
				. " WHERE abbr = ?";
			
			$projectOptions = Config::me()->getOption('project');
			
			$dbResult = Database::me()->query(
				$dbQuery,
				array($projectOptions['defaultLanguage'])
			);

			if(Database::me()->recordCount($dbResult))
			{
				$dbRow = Database::me()->fetchArray($dbResult);
				$this->setLanguageAbbr($dbRow['abbr']);
				$this->setLanguageId($dbRow['id']);
			}
				
			$this->setSource(self::SOURCE_LANGUAGE_DEFAULT);
			
			return $this;
		}
	}
?>