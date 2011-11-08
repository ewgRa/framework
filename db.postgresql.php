<?php
	/**
	 * Класс, расширяющий возможности класса для доступа к БД
	 * Данный класс сделан "автономным", то есть он обладает всеми знаниями где и какие настройки ему надо взять
	 * это сделано специально, чтобы была возможность инициализировать класс как можно "дальше" по коду либо вообще
	 * его не дергать, если доступ к БД не требуется
	 */
	class PostgresqlDB extends BasePostgresqlDB
	{
		public $Log = array();
		
		/**
		 * Список таблиц, все скрипты обращаются к имени таблицы по алиасу
		 * Переменная необходима для удобного добавления префикса при установке куда-либо 
		 * заполняется конструктором из yaml файла
		 * @var array
		 */
		public $TABLES = array();

		/**
		 * Настройки для соединения с БД
		 * заполняется конструктором из yaml файла
		 * @var array
		 */
		public $ConnectSettings = array();
		
		/**
		 * Список email адресов, на которые необходимо отослать отчет об ошибке
		 */
		public $ErrorReportEmails = array();
		
		
		function __construct()
		{
		}
		
		
		function __destruct()
		{
			if( $this->isConnect() ) $this->Disconnect();	
		}
		
		/**
		 * Соединение с БД
		 * @return boolean
		 */
		function Connect()
		{
			parent::Connect( $this->ConnectSettings['host'], $this->ConnectSettings['user'], $this->ConnectSettings['password'], $this->ConnectSettings['database'] );
			$this->SetCharset( $this->ConnectSettings['charset'] );
			return true;
		}
		
		/**
		 * Выполнение SQL запроса, если подключение к БД не было произведено - оно будет инициализировано здесь
		 * @param string $Query - текст запроса
		 * @param string $Values - аргументы, подставляемые в запрос
		 * @return RecordSet
		 */
		function Query( $Query, $Values = array() )
		{
			if( !$this->isConnect() ) $this->Connect();

			if( Config::getOption( 'Debug mode' ) )
			{
				$trace = debug_backtrace();
				if( array_key_exists( 1, $trace ) ) $trace = $trace[1];
				else $trace = $trace[0];
				$start = microtime( true );
				$result = parent::Query( $Query, $Values );
				$end = microtime( true );
				$this->Log[] = array( 'sql' => parent::ProcessQuery( $Query, $Values ), 'script' => @$trace['file'] . ' at line ' . @$trace['line'], 'time' => $end - $start );
				return $result;
			}
			else 
			{
				return parent::Query( $Query, $Values );
			}
		}
	}
	

	/**
	 * Базовый класс-обертка к функциям доступа к БД
	 */
	class BasePostgresqlDB
	{
		/**
		 * Подсчет количества запросов к БД
		 * @var int
		 */
		var $QueryCounts = 0;
		
		/**
		 * Флаг, показывающий что коннект уже произведен
		 * @var boolean
		 */
		private $Connected = false;
		
		private $link = null;
		
		/**
		 * Установка соединения с БД
		 * @param string $Host - хост
		 * @param string $User - пользователь БД
		 * @param string $Password - пароль к БД
		 * @return boolean
		 */
		function Connect( $Host, $User, $Password, $DatabaseName )
		{
			$db = 
				@pg_connect(
					join(
						' ',
						array(
							'host='.$Host,
							'user='.$User,
							'password='.$Password,
							'dbname='.$DatabaseName
						)
					)
				);
			
			if ( !$db ) throw new ExceptionMap::$Classes['DatabaseExceptionClass']( 
							serialize( array( 
								'text' => 'Could not connect to Database',
								'host' => $Host,
								'user' => $User,
								'password' => $Password,
								'database' => $DatabaseName
							) ), DataBaseException::CONNECT );
			
			$this->Connected = true;
			$this->link = $db;
			return true;
		}
		
		/**
		 * Выбрать базу данных
		 * @param string $Database - имя базы данных
		 * @return boolean
		 */
		function SelectDatabase( $Database )
		{
			throw new Exception();
		}
		
		/**
		 * Установка кодировки соединения с БД
		 * @param string $Charset
		 * @return boolean
		 */
		function SetCharset( $Charset = 'utf8' )
		{
			pg_setclientencoding($this->link, $Charset);
			return true;
		}

		/**
		 * Разорвать соединение с БД
		 * @return boolean
		 */
		function Disconnect()
		{
			pg_close($this->link);
			$this->Connected = false;
			return true;
		}

		/**
		 * Установлено ли соединение
		 * @return boolean
		 */
		function isConnect()
		{
			return $this->Connected;
		}

		/**
		 * Эскейп строки или элементов массива
		 * @param mixed $Variables
		 * @return mixed
		 */
		function Escape( $Variables )
		{
			if( is_array( $Variables ) )
			{
				foreach( $Variables as &$value ) $value = $this->Escape( $value );
			}
			else $Variables = pg_escape_string($this->link, $Variables);
			return $Variables;
		}

		function escapeField($field)
		{
			return '"'.$field.'"';
		}
		
		function escapeTable($table)
		{
			return '"'.$table.'"';
		}
		
		/**
		 * Обработка запроса, замена знаков вопроса на аргументы
		 * @param string $Query
		 * @param mixed $Values
		 * @return string
		 */
		function ProcessQuery( $Query, $Values = array() )
		{
			$Query = str_replace( '?', '??', $Query );
			$QueryParts = explode( '?', $Query );
			$i = 0;
			foreach( $QueryParts as &$Part )
			{
				if( $i%2 )
				{
					if( !is_null( key( $Values ) ) )
					{
						$Value = $Values[key( $Values )];
						if( is_null( $Value ) ) $Part = "NULL";
						else 
						{
							$Value = $this->Escape( $Value );
							if( is_array( $Value ) ) $Part = "'" . join( "', '", $Value ) . "'";
							else $Part = "'" . $Value . "'";
						}
						next( $Values );
					}
					else $Part = "?";
				}
				$i++;
			}
			return join( '', $QueryParts );
		}
		
		/**
		 * Выполнить SQL запрос
		 * @param string $dbq - запрос со знаками вопроса вместо реальных данных, реальные данные передаются через аргументы запроса
		 * @param array $Values - аргументы запроса, которые вставляются в запрос
		 * @return RecordSet
		 */
		function Query( $Query, $Values = array() )
		{
			if( count( $Values ) )
			{
				$Query = $this->ProcessQuery( $Query, $Values );
			}
			$Resource = @pg_query( $this->link, $Query );
			if( pg_result_error($Resource) ) throw new ExceptionMap::$Classes['DatabaseExceptionClass']( serialize( array( 'query' => $Query, 'error' => '' ) ), DataBaseException::SQL_QUERY_ERROR );
			$this->QueryCounts++;
			return $Resource;
		}

		/**
		 * Возващает количество записей RecordSet
		 * @param RecordSet $Resource
		 * @return int
		 */
		function RecordCount( $Resource )
		{
			return pg_num_rows( $Resource);
		}

		/**
		 * Возвращает следующую строку RecordSet'а в виде массива
		 * @param RecordSet $Resource
		 * @return array
		 */
		function FetchArray( $Resource )
		{
			return pg_fetch_assoc( $Resource);
		}

		/**
		 * Установка указателя на $Row строку
		 * @param RecordSet $Resource
		 * @param int $Row
		 * @return boolean
		 */
		function DataSeek( $Resource, $Row )
		{
			$Row--;
			pg_result_seek( $Resource, $Row );	
			return true;
		}

		/**
		 * Возвращает RecordSet в виде массив
		 * @param RecordSet $Resource
		 * @param string $Field - указывается, если неоходимо создать плоский массив из елементов одного поля
		 * @return mixed
		 */
		function ResourceToArray( $Resource, $Field = null )
		{
			$result = array();
			if( $Resource && $this->RecordCount( $Resource ) )
			{
				$this->DataSeek( $Resource, 1 );
				while ( $row = $this->FetchArray( $Resource ) )
				{
					$result[] = is_null( $Field ) ? $row : $row[$Field];
				}
			}
			return $result;
		}
		
		/**
		 * Возвращает SQL добавку к запросу для LIMIT
		 * @param int $Count - сколько записей взять
		 * @param int $From - с какой позиции брать записи
		 * @return string
		 */
		function GetLimit( $Count = null, $From = null )
		{
			$limit = array();
			if( $From < 0 ) $From = 0;
			if( $Count < 0 ) $Count = 0;
			if( !is_null( $From ) ) $limit[] = (int)$From;
			if( !is_null( $Count ) ) $limit[] = (int)$Count;
			return count( $limit ) ? ' LIMIT ' . join( ' OFFSET ', $limit ) : '';
		}

		/**
		 * Последний вставленный ID
		 * @return int
		 */
		function InsertID()
		{
			throw new Exception();
		}
	}
?>