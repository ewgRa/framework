<?php
	/* $Id$ */

	class LocalizerTest extends UnitTestCase
	{
		private $savedLocalizer = null;
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
			if(Singleton::hasInstance('Localizer'))
				$this->savedLocalizer = serialize(Localizer::me());

			DatabaseMock::create();
			Singleton::dropInstance('Localizer');
		}
		
		public function tearDown()
		{
			DatabaseMock::drop();

			if($this->savedLocalizer)
			{
				Singleton::setInstance(
					'Session',
					unserialize($this->savedLocalizer)
				);
				
				$this->savedLocalizer = null;
			}
			else
				Singleton::dropInstance('Localizer');
		}
		
		public function testIsSingleton()
		{
			$class = new ReflectionClass('Localizer');
			
			$this->assertTrue($class->isSubclassOf('Singleton'));
		}
		
		public function testGetLanguages()
		{
			$poolMock = DatabasePoolMock::create();
			
			$poolMock->setReturnValueAt(
				0,
				'resourceToArray',
				array(
					$this->languageToDbArray($this->languages[0]),
					$this->languageToDbArray($this->languages[1])
				)
			);
			
			Database::me()->setReturnValue('getPool', $poolMock);
			
			$localizer = LocalizerPathBased::create();
			
			$localizer->loadLanguages();
			
			$this->assertEqual(
				$this->convertLanguages($this->languages),
				$localizer->getLanguages()
			);
		}
		
		public function testSelectDefaultLanguage()
		{
			$localizer = LocalizerPathBased::create();
			
			$localizer->setLanguages(
				$this->convertLanguages($this->languages)
			);
			
			$localizer->selectDefaultLanguage('ru');
			
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
				$localizer->getSource(), BaseLocalizer::SOURCE_LANGUAGE_COOKIE
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
			
			$poolMock = DatabasePoolMock::create();
			
			$poolMock->setReturnValueAt(
				0,
				'resourceToArray',
				array(
					array('id'=> 1, 'abbr' => 'ru'),
					array('id'=> 2, 'abbr' => 'en')
				)
			);
						
			Database::me()->setReturnValue('getPool', $poolMock);
			
			$localizer->loadLanguages()->defineLanguage();

			
			$this->assertEqual(
				$localizer->getSource(),
				BaseLocalizer::SOURCE_LANGUAGE_URL_AND_COOKIE
			);

			$this->assertEqual($localizer->getRequestLanguage()->getId(), 1);
			$this->assertEqual($localizer->getRequestLanguage()->getAbbr(), 'ru');
		}
		
		public function testDefineLanguageUrl()
		{
			$localizer = LocalizerPathBased::create();
			$localizer->setPath('/ru/test');

			$poolMock = DatabasePoolMock::create();
			
			$poolMock->setReturnValueAt(
				0,
				'resourceToArray',
				array(
					array('id'=> 1, 'abbr' => 'ru'),
					array('id'=> 2, 'abbr' => 'en')
				)
			);
						
			Database::me()->setReturnValue('getPool', $poolMock);
			
			$localizer->loadLanguages()->defineLanguage();

			
			$this->assertEqual(
				$localizer->getSource(), BaseLocalizer::SOURCE_LANGUAGE_URL
			);

			$this->assertEqual($localizer->getRequestLanguage()->getId(), 1);
			$this->assertEqual($localizer->getRequestLanguage()->getAbbr(), 'ru');
		}
		
		public function testDefineLanguageDefault()
		{
			$localizer = LocalizerPathBased::create();
			
			$localizer->setPath('/miracle/test');

			$localizer->setLanguages(
				$this->convertLanguages($this->languages)
			);
						
			$localizer->selectDefaultLanguage('ru')->defineLanguage();

			
			$this->assertEqual(
				$localizer->getSource(), BaseLocalizer::SOURCE_LANGUAGE_DEFAULT
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