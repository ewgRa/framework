<?php
	/**
	 * Класс для ловли исключений, связанных со определнием страницы и т.п.
	 */
	class PageException extends Exception
	{
		# Выбрасывается в случае, если у пользователя нету прав для доступа к какой-нибудь странице
		const NO_RIGHTS = 2001;
		# Выбрасывается, если запрошенная страница не была найдена в системе
		const PAGE_NOT_DEFINED = 2002;

		
		public function __toString()
		{
			$ResultString = parent::__toString();
			switch( $this->code )
			{
				case self::NO_RIGHTS:
					$message = unserialize( $this->message );
					$ResultString = __CLASS__ . ": [{$this->code}]:\n\n<br/><br/>" . $message['text'] . "\n\n<br/><br/>";
				break;
				case self::PAGE_NOT_DEFINED:
					header( 'HTTP/1.1 404 Not Found' );
					$ResultString = __CLASS__ . ": [{$this->code}]:\n\n<br/><br/>" . $this->message . "\n\n<br/><br/>";
				break;				
			}
			return $ResultString;
		}
	}
?>