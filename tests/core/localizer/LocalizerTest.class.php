<?php
	class LocalizerTest extends UnitTestCase
	{
		private $languages = array(
			array('id' => 1, 'abbr' => 'ru'),
			array('id' => 2, 'abbr' => 'en')
		);
		
		public function setUp()
		{
			DatabaseMock::create();
			CacheMock::create();
			SessionMock::create();
			Singleton::dropInstance('Localizer');
		}
		
		public function tearDown()
		{
			DatabaseMock::drop();
			CacheMock::drop();
			SessionMock::drop();
			Singleton::dropInstance('Localizer');
		}
		
		public function testGetLanguages()
		{
			Cache::me()->setReturnValue('isExpired', true);
			
			Database::me()->setReturnValueAt(0, 'fetchArray', $this->languages[0]);
			Database::me()->setReturnValueAt(1, 'fetchArray', $this->languages[1]);
			
			$this->assertEqual(
				$this->convertLanguages($this->languages),
				Localizer::me()->getLanguages()
			);
		}
		
		public function testGetLanguagesFromCache()
		{
			Cache::me()->setReturnValue(
				'get', $this->convertLanguages($this->languages)
			);
			
			$this->assertEqual(
				$this->convertLanguages($this->languages),
				Localizer::me()->getLanguages()
			);
		}
		
		public function testSelectDafaultLanguage()
		{
			Cache::me()->setReturnValue('isExpired', true);
			
			Database::me()->setReturnValueAt(0, 'recordCount', 1);
			Database::me()->setReturnValueAt(0, 'fetchArray', $this->languages[0]);

			Localizer::me()->selectDefaultLanguage();
			
			$this->assertEqual(
				$this->languages[0]['id'],
				Localizer::me()->getLanguageId()
			);

			$this->assertEqual(
				$this->languages[0]['abbr'],
				Localizer::me()->getLanguageAbbr()
			);
		}
		
		public function testDefineLanguageCookie()
		{
			$cookieLanguage = array('id' => 2, 'abbr' => 'en');
			Localizer::me()->setCookieLanguage($cookieLanguage);

			Localizer::me()->setDeterminantRealization(
				LocalizerPathUrlDeterminant::create()
			);
			
			Localizer::me()->defineLanguage();
			
			$this->assertEqual(
				Localizer::me()->getSource(), Localizer::SOURCE_LANGUAGE_COOKIE
			);

			$this->assertEqual(Localizer::me()->getLanguageId(), $cookieLanguage['id']);
			$this->assertEqual(Localizer::me()->getLanguageAbbr(), $cookieLanguage['abbr']);
		}
		
		public function testDefineLanguageUrlAndCookie()
		{
			$cookieLanguage = array('id' => 2, 'abbr' => 'en');
			Localizer::me()->setCookieLanguage($cookieLanguage);

			Localizer::me()->setDeterminantRealization(
				LocalizerPathUrlDeterminant::create()->
					setUrl('/ru/test')
			);
			
			Cache::me()->setReturnValue('get', array(1 => 'ru', 2 => 'en'));
			
			Localizer::me()->defineLanguage();

			
			$this->assertEqual(
				Localizer::me()->getSource(),
				Localizer::SOURCE_LANGUAGE_URL_AND_COOKIE
			);

			$this->assertEqual(Localizer::me()->getLanguageId(), 1);
			$this->assertEqual(Localizer::me()->getLanguageAbbr(), 'ru');
		}
		
		public function testDefineLanguageUrl()
		{
			Localizer::me()->setDeterminantRealization(
				LocalizerPathUrlDeterminant::create()->
					setUrl('/ru/test')
			);

			Cache::me()->setReturnValue('get', array(1 => 'ru', 2 => 'en'));
			
			Localizer::me()->defineLanguage();

			
			$this->assertEqual(
				Localizer::me()->getSource(), Localizer::SOURCE_LANGUAGE_URL
			);

			$this->assertEqual(Localizer::me()->getLanguageId(), 1);
			$this->assertEqual(Localizer::me()->getLanguageAbbr(), 'ru');
		}
		
		public function testDefineLanguageDefault()
		{
			Localizer::me()->setDeterminantRealization(
				LocalizerPathUrlDeterminant::create()->
					setUrl('/miracle/test')
			);

			Cache::me()->setReturnValueAt(0, 'get', array(1 => 'ru', 2 => 'en'));
			Cache::me()->setReturnValueAt(1, 'get', array('id' => 1, 'abbr' => 'ru'));
			
			Localizer::me()->defineLanguage();

			
			$this->assertEqual(
				Localizer::me()->getSource(), Localizer::SOURCE_LANGUAGE_DEFAULT
			);

			$this->assertEqual(Localizer::me()->getLanguageId(), 1);
			$this->assertEqual(Localizer::me()->getLanguageAbbr(), 'ru');
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