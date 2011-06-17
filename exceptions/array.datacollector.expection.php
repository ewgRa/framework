<?php
	/**
	 * Класс для ловли исключений, связанных со определнием страницы и т.п.
	 */
	class ArrayDataCollectorException extends Exception
	{
		# Выбрасывается в случае, если была попытка добавить данные с уже существующим префиксом
		const UNIQUE_FAILED = 3001;

		
		public function __construct( $Message, $Code )
		{
			parent::__construct( $Message, $Code );	
		}
		
		
		public function __toString()
		{
			$ResultString = parent::__toString();
			switch( $this->code )
			{
				case self::UNIQUE_FAILED:
					$ResultString = __CLASS__ . ": [{$this->code}]:\n\n<br/><br/>" . $this->message . "\n\n<br/><br/>";
				break;				
			}
			return $ResultString;
		}
	}
?>