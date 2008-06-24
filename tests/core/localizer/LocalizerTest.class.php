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
			
			Localizer::me()->loadLanguages();
			
			$this->assertEqual(
				$this->convertLanguages($this->languages),
				Localizer::me()->getLanguages()
			);
		}
		
		public function testSelectDefaultLanguage()
		{
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
			Localizer::me()->setCookieLanguage($cookieLanguage['id'], $cookieLanguage['abbr']);

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
			Localizer::me()->setCookieLanguage($cookieLanguage['id'], $cookieLanguage['abbr']);

			Localizer::me()->setDeterminantRealization(
				LocalizerPathUrlDeterminant::create()->
					setUrl('/ru/test')
			);
			
			Database::me()->setReturnValueAt(0, 'fetchArray', array('id'=> 1, 'abbr' => 'ru'));
			Database::me()->setReturnValueAt(1, 'fetchArray', array('id'=> 2, 'abbr' => 'en'));
						
			Localizer::me()->
				loadLanguages()->
				defineLanguage();

			
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

			Database::me()->setReturnValueAt(0, 'fetchArray', array('id'=> 1, 'abbr' => 'ru'));
			Database::me()->setReturnValueAt(1, 'fetchArray', array('id'=> 2, 'abbr' => 'en'));
						
			Localizer::me()->
				loadLanguages()->
				defineLanguage();

			
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

			Database::me()->setReturnValueAt(0, 'recordCount', 1);
			Database::me()->setReturnValueAt(0, 'fetchArray', array('id'=> 1, 'abbr' => 'ru'));
			Database::me()->setReturnValueAt(0, 'fetchArray', array('id'=> 1, 'abbr' => 'ru'));
			Database::me()->setReturnValueAt(1, 'fetchArray', array('id'=> 2, 'abbr' => 'en'));
			
			Localizer::me()->
				selectDefaultLanguage()->
				defineLanguage();

			
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