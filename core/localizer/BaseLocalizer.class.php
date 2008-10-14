<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	abstract class BaseLocalizer implements BaseLocalizerInterface
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
		 * @return BaseLocalizer
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		/**
		 * @return BaseLocalizer
		 */
		public static function factory($realization)
		{
			return
				self::setInstance('Localizer', new $realization);
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
		 * @return BaseLocalizer
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
		 * @return BaseLocalizer
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
		 * @return BaseLocalizer
		 */
		public function setLanguages(array $languages)
		{
			$this->languages = $languages;
			return $this;
		}
		
		/**
		 * @return BaseLocalizer
		 */
		public function setCookieLanguage(Language $language)
		{
			$this->cookieLanguage = $language;
			return $this;
		}
		
		/**
		 * @return BaseLocalizer
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
		 * @return BaseLocalizer
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
		 * @return BaseLocalizer
		 */
		public function selectDefaultLanguage($languageAbbr)
		{
			$language = Language::create()->setAbbr($languageAbbr);
			
			if($this->getLanguages())
			{
				$flipLang = array_flip($this->getLanguages());

				if(isset($flipLang[$languageAbbr]))
					$language->setId($flipLang[$languageAbbr]);
			}

			if($language->getId())
			{
				$this->setRequestLanguage($language);
				$this->setSource(self::SOURCE_LANGUAGE_DEFAULT);
			}
			else
			{
				throw ExceptionsMapper::me()->createException('Default')->
					setMessage(
						'Known nothing about default language '
						. '"' . $languageAbbr . '"'
					);
			}
			
			return $this;
		}
	}
?>