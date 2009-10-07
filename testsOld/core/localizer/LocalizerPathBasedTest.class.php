<?php
	/* $Id$ */

	class LocalizerPathBasedTest extends UnitTestCase
	{
		public function testDefineLanguagePath()
		{
			$determinant = LocalizerPathBased::create();

			$determinant->setPath('/thisIsLanguage/thisIsUrl.html');
			$this->assertEqual(
				$determinant->getDefinedLanguageAbbr(),
				'thisIsLanguage'
			);
		}

		public function testCutLanguageAbbr()
		{
			$determinant = LocalizerPathBased::create();
			
			$determinant->
				setPath('/thisIsLanguage/thisIsUrl.html')->
				setRequestLanguage(
					Language::create()->setAbbr('thisIsLanguage')
				);
			
			$this->assertEqual(
				$determinant->cutLanguageAbbr(),
				'/thisIsUrl.html'
			);

			$determinant->setPath(
				'/thisIsLanguage/nuln/thisIsLanguage/thisIsUrl.html?test=f'
			);
			
			$this->assertEqual(
				$determinant->cutLanguageAbbr(),
				'/nuln/thisIsLanguage/thisIsUrl.html?test=f'
			);
		}
	}
?>