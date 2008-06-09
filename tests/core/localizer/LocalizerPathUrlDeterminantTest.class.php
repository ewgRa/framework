<?php
	class LocalizerPathUrlDeterminantTest extends UnitTestCase
	{
		public function testDefineLanguageUrl()
		{
			$determinant = LocalizerPathUrlDeterminant::create();

			$determinant->setUrl('/thisIsLanguage/thisIsUrl.html');
			$this->assertEqual(
				$determinant->getDefinedLanguageAbbr(),
				'thisIsLanguage'
			);
			
			$determinant->setUrl('http://test.ru/thisIsLanguage/thisIsUrl.html');
			$this->assertEqual(
				$determinant->getDefinedLanguageAbbr(),
				'thisIsLanguage'
			);
		}

		public function testCutLanguageAbbr()
		{
			$determinant = LocalizerPathUrlDeterminant::create();
			
			$determinant->setUrl('/thisIsLanguage/thisIsUrl.html');
			
			$this->assertEqual(
				$determinant->cutLanguageAbbr('thisIsLanguage'),
				'/thisIsUrl.html'
			);

			$determinant->setUrl('http://test.ru/thisIsLanguage/thisIsUrl.html');
			
			$this->assertEqual(
				$determinant->cutLanguageAbbr('thisIsLanguage'),
				'http://test.ru/thisIsUrl.html'
			);

			$determinant->setUrl(
				'http://test.ru:80/thisIsLanguage/nuln/thisIsLanguage/thisIsUrl.html?test=f'
			);
			
			$this->assertEqual(
				$determinant->cutLanguageAbbr('thisIsLanguage'),
				'http://test.ru:80/nuln/thisIsLanguage/thisIsUrl.html?test=f'
			);

			$determinant->setUrl(
				'http://test.ru:80/nuln/thisIsLanguage/thisIsUrl.html?test=f'
			);
			
			$this->assertEqual(
				$determinant->cutLanguageAbbr('thisIsLanguage'),
				'http://test.ru:80/nuln/thisIsLanguage/thisIsUrl.html?test=f'
			);
		}
	}
?>