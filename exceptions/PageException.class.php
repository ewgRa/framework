<?php
	class PageException extends DefaultException
	{
		const PAGE_NOT_FOUND = 1001;
		const NO_RIGHTS_TO_ACCESS = 1002;
		
		private $url = null;
		private $noRights = null;
		private $pageRights = null;
		
		public function setUrl($url)
		{
			$this->url = $url;
			return $this;
		}
		
		public function setNoRights($noRights)
		{
			$this->noRights = $noRights;
			return $this;
		}
		
		public function setPageRights($rights)
		{
			$this->pageRights = $rights;
			return $this;
		}
		
		public function __toString()
		{
			$resultString = parent::__toString();
			
			switch( $this->code )
			{
				case self::PAGE_NOT_FOUND:

					if(!$this->message)
					{
						$this->setMessage('Page not found!');
					}
					
					$resultString =
						__CLASS__
						. ": [{$this->code}]:\n\n{$this->message}\n\n"
						. "Url: {$this->url}\n\n";
				break;
				case self::NO_RIGHTS_TO_ACCESS:

					if(!$this->message)
					{
						$this->setMessage('No rights for access to page');
					}
					
					$resultString =
						__CLASS__
						. ": [{$this->code}]:\n\n{$this->message}\n\n"
						. "No rights: " . serialize($this->noRights) . "\n\n"
						. "Page rights: " . serialize($this->pageRights) . "\n\n";
					break;
			}
			
			return $resultString;
		}
	}
?>