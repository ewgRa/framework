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
		
		private $language = array('abbr' => null, 'id' => null);
		private $cookieLanguage = null;
		private $source = self::SOURCE_LANGUAGE_DEFAULT;
		private $determinantRealization = null;
		
		protected static $instance = null;
		
		/**
		 * @return Localizer
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__, self::$instance);
		}
		
		public function getLanguageAbbr()
		{
			return $this->language['abbr'];
		}
		
		private function setLanguageAbbr($abbr)
		{
			$this->language['abbr'] = $abbr;
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
			return $this->language['id'];
		}
		
		private function setLanguageId($id)
		{
			$this->language['id'] = $id;
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
		
		public function setCookieLanguage($language)
		{
			$this->cookieLanguage = $language;
			return $this;
		}
		
		public function defineLanguage()
		{
			if($this->cookieLanguage)
			{
				$this->setLanguageId($this->cookieLanguage['id'])->
					setLanguageAbbr($this->cookieLanguage['abbr']);
					
				$this->setSource(self::SOURCE_LANGUAGE_COOKIE);
			}

			$probableLanguageAbbr = $this->getDeterminantRealization()->
					getDefinedLanguageAbbr();

			$languages = $this->getLanguages();
			
			if($languages && in_array($probableLanguageAbbr, $languages))
			{
				$this->setLanguageAbbr($probableLanguageAbbr);
				$flipLanguages = array_flip($languages);
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

			if($this->getSource() == self::SOURCE_LANGUAGE_DEFAULT)
			{
				$this->selectDefaultLanguage();
			}

			return $this;
		}

		public function getLanguages()
		{
			$languages = Cache::me()->get(
				array(__CLASS__, __FUNCTION__),
				'site/languages'
			);
			
			if(Cache::me()->isExpired())
			{
				$dbQuery = "SELECT * FROM " . Database::me()->getTable('Languages');
				$languages = array();
				$dbResult = Database::me()->query($dbQuery);

				while($dbRow = Database::me()->fetchArray($dbResult))
				{
					$languages[$dbRow['id']] = $dbRow['abbr'];
				}

				Cache::me()->set($languages, time() + self::CACHE_LIFE_TIME);
			}
			return $languages;
		}

		public function selectDefaultLanguage()
		{
			
			$this->language = Cache::me()->get(
				array(__CLASS__, __FUNCTION__),
				'site/languages'
			);
			
			if(Cache::me()->isExpired())
			{
				$dbQuery = "SELECT t1.* FROM " . Database::me()->getTable('Languages')
					. " t1 INNER JOIN " . Database::me()->getTable('Options')
					. " t2 ON ( t2.alias = 'defaultLanguage' AND t2.value = t1.id )";
					
				$this->language = array('abbr' => null, 'id' => null);
				$dbResult = Database::me()->query($dbQuery);

				if(Database::me()->recordCount($dbResult))
				{
					$dbRow = Database::me()->fetchArray($dbResult);
					$this->setLanguageAbbr($dbRow['abbr']);
					$this->setLanguageId($dbRow['id']);
				}
				
		        Cache::me()->set($this->language, time() + self::CACHE_LIFE_TIME);
			}
			
			return $this;
		}
	}
?>
