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
		
		private $requestLanguage = null;
		private $languages 		 = null;
		private $cookieLanguage  = null;
		private $source 		 = null;
		
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
		
		public function getRequestLanguage()
		{
			return $this->requestLanguage;
		}
		
		/**
		 * @return Localizer
		 */
		public function setRequestLanguage(Language $language)
		{
			$this->requestLanguage = $language;
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
		
		public function getLanguages()
		{
			return $this->languages;
		}
		
		/**
		 * @return Localizer
		 */
		public function setCookieLanguage(Language $language)
		{
			$this->cookieLanguage = $language;
			return $this;
		}
		
		/**
		 * @return Localizer
		 */
		public function defineLanguage()
		{
			if($this->cookieLanguage)
			{
				$this->setRequestLanguage($this->cookieLanguage);
				$this->setSource(self::SOURCE_LANGUAGE_COOKIE);
			}

			$probableLanguageAbbr = $this->getDefinedLanguageAbbr();

			if($this->languages && in_array($probableLanguageAbbr, $this->languages))
			{
				$flipLanguages = array_flip($this->languages);
				
				$this->setRequestLanguage(
					Language::create()->
						setAbbr($probableLanguageAbbr)->
						setId($flipLanguages[$probableLanguageAbbr])
				);

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
				$this->setRequestLanguage(
					Language::create()->
						setId($dbRow['id'])->
						setAbbr($dbRow['abbr'])
				);
			}
				
			$this->setSource(self::SOURCE_LANGUAGE_DEFAULT);
			
			return $this;
		}
	}
?>