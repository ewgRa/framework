<?php
	/* $Id$ */

	class LocalizerTest extends UnitTestCase
	{
		private $languages = null;
		
		public function __construct()
		{
			$this->languages = array(
				Language::create()->setId(1)->setAbbr('ru'),
				Language::create()->setId(2)->setAbbr('en')
			);
			
			return parent::__construct();
		}
		
		public function setUp()
		{
			DatabaseMock::create();
			SessionMock::create();
			Singleton::dropInstance('Localizer');
		}
		
		public function tearDown()
		{
			DatabaseMock::drop();
			SessionMock::drop();
			Singleton::dropInstance('Localizer');
		}
		
		public function testGetLanguages()
		{
			Database::me()->setReturnValueAt(
				0,
				'fetchArray',
				$this->languageToDbArray($this->languages[0])
			);
			
			Database::me()->setReturnValueAt(
				1,
				'fetchArray',
				$this->languageToDbArray($this->languages[1])
			);
						
			$localizer = LocalizerPathBased::create();
			
			$localizer->loadLanguages();
			
			$this->assertEqual(
				$this->convertLanguages($this->languages),
				$localizer->getLanguages()
			);
		}
		
		public function testSelectDefaultLanguage()
		{
			Database::me()->setReturnValueAt(0, 'recordCount', 1);
			
			Database::me()->setReturnValueAt(
				0,
				'fetchArray',
				$this->languageToDbArray($this->languages[0])
			);
			
			$localizer = LocalizerPathBased::create();
			$localizer->selectDefaultLanguage();
			
			$this->assertEqual(
				$this->languages[0],
				$localizer->getRequestLanguage()
			);
		}
		
		public function testDefineLanguageCookie()
		{
			$cookieLanguage = array('id' => 2, 'abbr' => 'en');
			$localizer = LocalizerPathBased::create();
			$localizer->setCookieLanguage(
				Language::create()->
					setId($cookieLanguage['id'])->
					setAbbr($cookieLanguage['abbr'])
			);

			$localizer->defineLanguage();
			
			$this->assertEqual(
				$localizer->getSource(), Localizer::SOURCE_LANGUAGE_COOKIE
			);

			$this->assertEqual(
				$localizer->getRequestLanguage()->getId(), $cookieLanguage['id']
			);
			$this->assertEqual(
				$localizer->getRequestLanguage()->getAbbr(), $cookieLanguage['abbr']
			);
		}
		
		public function testDefineLanguageUrlAndCookie()
		{
			$cookieLanguage = Language::create()->setId(2)->setAbbr('en');

			$localizer = LocalizerPathBased::create();
			$localizer->setCookieLanguage($cookieLanguage);

			$localizer->setPath('/ru/test');
			
			Database::me()->setReturnValueAt(0, 'fetchArray', array('id'=> 1, 'abbr' => 'ru'));
			Database::me()->setReturnValueAt(1, 'fetchArray', array('id'=> 2, 'abbr' => 'en'));
						
			$localizer->loadLanguages()->defineLanguage();

			
			$this->assertEqual(
				$localizer->getSource(),
				Localizer::SOURCE_LANGUAGE_URL_AND_COOKIE
			);

			$this->assertEqual($localizer->getRequestLanguage()->getId(), 1);
			$this->assertEqual($localizer->getRequestLanguage()->getAbbr(), 'ru');
		}
		
		public function testDefineLanguageUrl()
		{
			$localizer = LocalizerPathBased::create();
			$localizer->setPath('/ru/test');

			Database::me()->setReturnValueAt(0, 'fetchArray', array('id'=> 1, 'abbr' => 'ru'));
			Database::me()->setReturnValueAt(1, 'fetchArray', array('id'=> 2, 'abbr' => 'en'));
						
			$localizer->loadLanguages()->defineLanguage();

			
			$this->assertEqual(
				$localizer->getSource(), Localizer::SOURCE_LANGUAGE_URL
			);

			$this->assertEqual($localizer->getRequestLanguage()->getId(), 1);
			$this->assertEqual($localizer->getRequestLanguage()->getAbbr(), 'ru');
		}
		
		public function testDefineLanguageDefault()
		{
			$localizer = LocalizerPathBased::create();
			$localizer->setPath('/miracle/test');

			Database::me()->setReturnValueAt(0, 'recordCount', 1);
			Database::me()->setReturnValueAt(0, 'fetchArray', array('id'=> 1, 'abbr' => 'ru'));
			Database::me()->setReturnValueAt(0, 'fetchArray', array('id'=> 1, 'abbr' => 'ru'));
			Database::me()->setReturnValueAt(1, 'fetchArray', array('id'=> 2, 'abbr' => 'en'));
			
			$localizer->selectDefaultLanguage()->defineLanguage();

			
			$this->assertEqual(
				$localizer->getSource(), Localizer::SOURCE_LANGUAGE_DEFAULT
			);

			$this->assertEqual($localizer->getRequestLanguage()->getId(), 1);
			$this->assertEqual($localizer->getRequestLanguage()->getAbbr(), 'ru');
		}
		
		public function convertLanguages($languages)
		{
			$result = array();
			
			foreach($this->languages as $language)
			{
				$result[$language->getId()] = $language->getAbbr();
			}
			
			return $result;
		}
		
		private function languageToDbArray(Language $language)
		{
			return
				array(
					'id'	=> $language->getId(),
					'abbr'	=> $language->getAbbr()
				);
		}
	}
?>