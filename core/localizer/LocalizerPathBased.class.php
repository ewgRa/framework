<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	final class LocalizerPathBased extends Localizer
	{
		protected $type = self::DETERMINANT_PATH_BASED;
		
		/**
		 * @return LocalizerPathBased
		 */
		public static function create()
		{
			return new self;
		}
		
		protected function getLanguageAbbr(HttpUrl $url)
		{
			$result = null;
			
			$parts = explode('/', $url->getPath());

			if(count($parts) > 2)
				$result = $parts[1];
			
			return $result;
		}
		
		public function getBaseUrl()
		{
			$result = parent::getBaseUrl();

			if(
				$this->isLanguageInUrl()
				&& $this->getSource() != Localizer::SOURCE_LANGUAGE_URL_AND_COOKIE
			) {
				$result = '/' . $this->getRequestLanguage()->getAbbr() . $result;
			}
			
			return $result;
		}

		/**
		 * @return HttpUrl
		 */
		public function removeLanguageFromUrl(HttpUrl $url)
		{
			return
				$this->isLanguageInUrl()
					? $this->cutLanguageAbbr($url)
					: $url;
		}

		/**
		 * @return HttpUrl
		 */
		private function cutLanguageAbbr(HttpUrl $url)
		{
			if(
				$this->getLanguageAbbr($url)
					== $this->getRequestLanguage()->getAbbr()
			) {
				$url->setPath(
					substr(
						$url->getPath(),
						strlen($this->getRequestLanguage()->getAbbr()) + 1
					)
				);
			}
			
			return $url;
		}
	}
?>