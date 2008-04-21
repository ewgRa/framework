<?php
	/**
	 * Класс для ловли исключений, связанных с базой данных
	 */
	class DataBaseException extends Exception
	{
		# Выбрасывается в случае, если не смогли соединиться с БД
		const CONNECT = 1001;

		# Выбрасывается в случае, если не смогли выбрать БД
		const SELECT_DATABASE = 1002;

		# Ошибка при выполнении SQL запроса
		const SQL_QUERY_ERROR = 1003;

		
		public function __construct( $Message, $Code )
		{
			parent::__construct( $Message, $Code );	
		}
		
		
		public function __toString()
		{
			$ResultString = parent::__toString();
			switch( $this->code )
			{
				case self::CONNECT:
					$message = unserialize( $this->message );
					$ResultString = __CLASS__ . ": [{$this->code}]:\n\n{$message['text']}\n\n";
				break;
				case self::SELECT_DATABASE :
					$ResultString = __CLASS__ . ": [{$this->code}]:\n\n{$this->message}\n\n";
				break;
				case self::SQL_QUERY_ERROR:
					$message = unserialize( $this->message );
					$trace = $this->getTrace();
					$single_trace = $trace[1];
					$file = $single_trace['file'];
					$line = $single_trace['line'];
					$ResultString = __CLASS__ . ": [{$this->code}]:\n\nQuery: {$message['query']}\n\nError: {$message['error']}\n\nQuery executed from: {$file} at line {$line}\n\n";
				break;				
			}
			return $ResultString;
		}
	}
?>