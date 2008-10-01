<?php
	/* $Id$ */

	class PageException extends DefaultException
	{
		const PAGE_NOT_FOUND		= 1001;
		const NO_RIGHTS_TO_ACCESS	= 1002;
		
		private $url		= null;
		private $noRights	= null;
		private $pageRights	= null;
		
		/**
		 * @return PageException
		 */
		public function setUrl($url)
		{
			$this->url = $url;
			return $this;
		}
		
		/**
		 * @return PageException
		 */
		public function setNoRights($noRights)
		{
			$this->noRights = $noRights;
			return $this;
		}
		
		/**
		 * @return PageException
		 */
		public function setPageRights($rights)
		{
			$this->pageRights = $rights;
			return $this;
		}
		
		public function __toString()
		{
			$resultString = array(parent::__toString());
			
			switch($this->code)
			{
				case self::PAGE_NOT_FOUND:

					if(!$this->message)
						$this->setMessage('Page not found!');
					
					$resultString = array(
						__CLASS__ . ": [{$this->code}]:",
						$this->message,
						"Url: {$this->url}"
					);
				break;

				case self::NO_RIGHTS_TO_ACCESS:

					if(!$this->message)
						$this->setMessage('No rights for access to page');
					
					$resultString = array(
						__CLASS__ . ": [{$this->code}]:",
						$this->message,
						"No rights: " . serialize($this->noRights),
						"Page rights: " . serialize($this->pageRights)
					);
				break;
			}
			
			$resultString[] = '';
			
			return join(PHP_EOL . PHP_EOL, $resultString);
		}
	}
?>