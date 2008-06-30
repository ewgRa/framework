<?php
	/* $Id$ */

	class LocalizerTest extends UnitTestCase
	{
		private $languages = array(
			array('id' => 1, 'abbr' => 'ru'),
			array('id' => 2, 'abbr' => 'en')
		);
		
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
			Database::me()->setReturnValueAt(0, 'fetchArray', $this->languages[0]);
			Database::me()->setReturnValueAt(1, 'fetchArray', $this->languages[1]);
			
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
			Database::me()->setReturnValueAt(0, 'fetchArray', $this->languages[0]);

			$localizer = LocalizerPathBased::create();
			$localizer->selectDefaultLanguage();
			
			$this->assertEqual(
				$this->languages[0]['id'],
				$localizer->getLanguageId()
			);

			$this->assertEqual(
				$this->languages[0]['abbr'],
				$localizer->getLanguageAbbr()
			);
		}
		
		public function testDefineLanguageCookie()
		{
			$cookieLanguage = array('id' => 2, 'abbr' => 'en');
			$localizer = LocalizerPathBased::create();
			$localizer->setCookieLanguage($cookieLanguage['id'], $cookieLanguage['abbr']);

			$localizer->defineLanguage();
			
			$this->assertEqual(
				$localizer->getSource(), Localizer::SOURCE_LANGUAGE_COOKIE
			);

			$this->assertEqual($localizer->getLanguageId(), $cookieLanguage['id']);
			$this->assertEqual($localizer->getLanguageAbbr(), $cookieLanguage['abbr']);
		}
		
		public function testDefineLanguageUrlAndCookie()
		{
			$cookieLanguage = array('id' => 2, 'abbr' => 'en');
			$localizer = LocalizerPathBased::create();
			$localizer->setCookieLanguage($cookieLanguage['id'], $cookieLanguage['abbr']);

			$localizer->setPath('/ru/test');
			
			Database::me()->setReturnValueAt(0, 'fetchArray', array('id'=> 1, 'abbr' => 'ru'));
			Database::me()->setReturnValueAt(1, 'fetchArray', array('id'=> 2, 'abbr' => 'en'));
						
			$localizer->loadLanguages()->defineLanguage();

			
			$this->assertEqual(
				$localizer->getSource(),
				Localizer::SOURCE_LANGUAGE_URL_AND_COOKIE
			);

			$this->assertEqual($localizer->getLanguageId(), 1);
			$this->assertEqual($localizer->getLanguageAbbr(), 'ru');
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

			$this->assertEqual($localizer->getLanguageId(), 1);
			$this->assertEqual($localizer->getLanguageAbbr(), 'ru');
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

			$this->assertEqual($localizer->getLanguageId(), 1);
			$this->assertEqual($localizer->getLanguageAbbr(), 'ru');
		}
		
		public function convertLanguages($languages)
		{
			$result = array();
			
			foreach($this->languages as $language)
			{
				$result[$language['id']] = $language['abbr'];
			}
			
			return $result;
		}
	}
?>