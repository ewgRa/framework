<?php
	class Localizer extends Singleton
	{
		const SOURCE_LANGUAGE_DEFAULT = 1;
		const SOURCE_LANGUAGE_COOKIE = 2;
		const SOURCE_LANGUAGE_URL = 3;
		const SOURCE_LANGUAGE_URL_AND_COOKIE = 4;
		
		const DETERMINANT_PATH_BASED = 5;
		const DETERMINANT_HOST_BASED = 6;
		
		const CACHE_LIFE_TIME = 86400;
		
		private $languageAbbr = null;
		private $languageId = null;
		private $languages = null;
		private $cookieLanguageId = null;
		private $cookieLanguageAbbr = null;
		private $source = null;
		private $determinantRealization = null;
		
		/**
		 * @return Localizer
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function getLanguageAbbr()
		{
			return $this->languageAbbr;
		}
		
		private function setLanguageAbbr($abbr)
		{
			$this->languageAbbr = $abbr;
			return $this;
		}

		public function getSource()
		{
			return $this->source;
		}
		
		private function setSource($source)
		{
			$this->source = $source;
			return $this;
		}
		
		public function getLanguageId()
		{
			return $this->languageId;
		}
		
		private function setLanguageId($id)
		{
			$this->languageId = $id;
			return $this;
		}

		public function getDeterminantRealization()
		{
			return $this->determinantRealization;
		}
		
		public function setDeterminantRealization($determinantRealization)
		{
			$this->determinantRealization = $determinantRealization;
			return $this;
		}
		
		public function setCookieLanguage($languageId, $languageAbbr)
		{
			$this->cookieLanguageId = $languageId;
			$this->cookieLanguageAbbr = $languageAbbr;
			return $this;
		}
		
		public function defineLanguage()
		{
			if($this->cookieLanguageId && $this->cookieLanguageAbbr)
			{
				$this->setLanguageId($this->cookieLanguageId)->
					setLanguageAbbr($this->cookieLanguageAbbr);
					
				$this->setSource(self::SOURCE_LANGUAGE_COOKIE);
			}

			$probableLanguageAbbr = $this->getDeterminantRealization()->
					getDefinedLanguageAbbr();

			if($this->languages && in_array($probableLanguageAbbr, $this->languages))
			{
				$this->setLanguageAbbr($probableLanguageAbbr);
				$flipLanguages = array_flip($this->languages);
				$this->setLanguageId($flipLanguages[$probableLanguageAbbr]);

				if($this->getSource() == self::SOURCE_LANGUAGE_COOKIE)
				{
					$this->setSource(self::SOURCE_LANGUAGE_URL_AND_COOKIE);
				}
				else
				{
					$this->setSource(self::SOURCE_LANGUAGE_URL);
				}
			}

			return $this;
		}

		public function loadLanguages()
		{
			$dbQuery = "SELECT * FROM " . Database::me()->getTable('Languages');
			$this->languages = array();
			$dbResult = Database::me()->query($dbQuery);

			while($dbRow = Database::me()->fetchArray($dbResult))
			{
				$this->languages[$dbRow['id']] = $dbRow['abbr'];
			}
			
			return $this;
		}

		public function getLanguages()
		{
			return $this->languages;
		}
		
		public function selectDefaultLanguage()
		{
			$dbQuery = "SELECT t1.* FROM " . Database::me()->getTable('Languages')
				. " t1 INNER JOIN " . Database::me()->getTable('Options')
				. " t2 ON ( t2.alias = 'defaultLanguage' AND t2.value = t1.id )";
				
			$dbResult = Database::me()->query($dbQuery);

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
