<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Browser
	{
		private $userAgent = null;

		public static function createFromUserAgent($userAgent)
		{
			$self = new self;

			$self->setUserAgent($userAgent);

			return $self;
		}

		public function isIE6()
		{
			return mb_stripos($this->userAgent, 'msie 6', 0, 'utf8') !== false;
		}

		private function setUserAgent($userAgent)
		{
			$this->userAgent = $userAgent;
		}
	}
?>