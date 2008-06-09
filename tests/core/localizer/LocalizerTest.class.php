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
		}
		
		public function tearDown()
		{
			DatabaseMock::drop();
			CacheMock::drop();
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