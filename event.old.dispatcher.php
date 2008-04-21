<?php
	/**
	 * Контроллер событий
	 */
	class EventOldDispatcher
	{
		/**
		 * Лог вызова событий
		 * @var array
		 */
		public $EventLog = array();
		public $EventNumber = 0;

		/**
		 * Уникальный идентификатор подписчика. Когда происходит подписка на событие, подписчику выдается уникальный идентификатор подписки, который потом передается в подписавшуюся функцию.
		 * Введен для упрощения ситуации когда какая-то функция подписывает кучу других функций
		 * @var int
		 */
		private $SubscriberUniqueID = 0;
		
		/**
		 * Кандидаты на подпись к событию. Возможна ситуация, когда кто-то подписывается на событие, которое не зарегистрировано
		 * Когда событие будет зарегистрировано, кандидаты будут подписаны на него автоматически
		 * @var array
		 */
		public $DataCandidates = array(
			'Providers' => array( /* 'EventName' => array() */ ), # источники данных
			'Receivers' => array( /* 'EventName' => array() */ ), # приемники данных
			'EasySubscribers' => array( /* 'EventName' => array() */ ) # простейшие подписчики
		);

		/**
		 * Таблица событий - стек событий можно сказать
		 * @var array
		 */
		public $EventTable = array(
			'OnStart' => array( # имя события
				'DeclaredBy' => 'Programm Code', # кто опубликовал собитие
				'DataProviders' => array(), # экземпляр класса с указанием какое событие служит источником данных
				'DataReceivers' => array(), # экземпляр класса с указанием какое событие служит приемником данных
				'EasySubscribers' => array(), # простейшие подписчики, которые выполняются по факту события и не служат ни источником, ни приемником данных
				'Fired' => false # произошло ли событие
			)
		);

		
		public function __construct()
		{
			$this->EventTable = array();
			$this->DataCandidates = array( 'Providers' => array(), 'Receivers' => array(), 'EasySubscribers' => array() );
		}
		
		/**
		 * Регистрация события
		 * @param string $EventName - имя события
		 * @param string $DeclaredBy - кто опубликовал событие
		 */
		public function RegisterEvent( $EventName, $DeclaredBy = null )
		{
			$this->EventLog[$EventName][] = array( 'Register', 'Time' => microtime( true ), 'EventNumber' => $this->EventNumber++, 'DeclaredBy' => $DeclaredBy );
			
			$this->EventTable[$EventName] = array(
				'DeclaredBy' => $DeclaredBy, 
				'DataProviders' => array(),
				'DataReceivers' => array(),
				'EasySubscribers' => array(),
				'Fired' => false
			);
			
			# Подписываем кандидатов
			if( array_key_exists( $EventName, $this->DataCandidates['Providers'] ) )
			{
				foreach ( $this->DataCandidates['Providers'][$EventName] as $SubscriberID => $Function )
				{
					$this->SubscribeAsDataProvider( $EventName, $Function, $SubscriberID );
				}
			}
			if( array_key_exists( $EventName, $this->DataCandidates['Receivers'] ) )
			{
				foreach ( $this->DataCandidates['Receivers'][$EventName] as $SubscriberID => $Function )
				{
					$this->SubscribeAsDataReceiver( $EventName, $Function, $SubscriberID );
				}
			}
			if( array_key_exists( $EventName, $this->DataCandidates['EasySubscribers'] ) )
			{
				foreach ( $this->DataCandidates['EasySubscribers'][$EventName] as $SubscriberID => $Function )
				{
					$this->SubscribeAsEasy( $EventName, $Function, $SubscriberID );
				}
			}
		}

		/**
		 * Зарегистрировано ли событие
		 * @param string $EventName - имя события
		 * @return boolean
		 */
		public function IsEventRegister( $EventName )
		{
			if( array_key_exists( $EventName, $this->EventTable ) ) return true;
			return false;
		}
		
		/**
		 * Подписаться на событие как простейшая выполняемая функция
		 * @param string $EventName - имя события
		 * @param mixed $Function - экземпляр класса с указанием метода который будет вызываться
		 */
		public function SubscribeAsEasy( $EventName, $Function, $SubscriberID = null )
		{
			if( !$SubscriberID ) $SubscriberID = $this->SubscriberUniqueID++;
			if( $this->IsEventRegister( $EventName ) )
			{
				$this->EventLog[$EventName][] = array( 'Subscribe', 'Time' => microtime( true ), 'As' => 'Easy', 'EventNumber' => $this->EventNumber++, 'Subscriber' => $this->CompileFunctionName( $Function ) );
				$this->EventTable[$EventName]['EasySubscribers'][$SubscriberID] = $Function;
			}
			else 
			{
				$this->EventLog[$EventName][] = array( 'Subscribe', 'Time' => microtime( true ), 'As' => 'Easy Candidate', 'EventNumber' => $this->EventNumber++, 'Subscriber' => $this->CompileFunctionName( $Function ) );
				$this->DataCandidates['EasySubscribers'][$EventName][$SubscriberID] = $Function;
			}
			return $SubscriberID;
		}

		/**
		 * Подписаться на событие как источник данных
		 * @param string $EventName - имя события
		 * @param mixed $Function - экземпляр класса с указанием метода который будет вызываться
		 */
		public function SubscribeAsDataProvider( $EventName, $Function, $SubscriberID = null )
		{
			if( !$SubscriberID ) $SubscriberID = $this->SubscriberUniqueID++;
			if( $this->IsEventRegister( $EventName ) )
			{
				$this->EventLog[$EventName][] = array( 'Subscribe', 'Time' => microtime( true ), 'EventNumber' => $this->EventNumber++, 'As' => 'Data Provider', 'Provider' => $this->CompileFunctionName( $Function ) );
				$this->EventTable[$EventName]['DataProviders'][$SubscriberID] = $Function;
			}
			else 
			{
				$this->EventLog[$EventName][] = array( 'Subscribe', 'Time' => microtime( true ), 'EventNumber' => $this->EventNumber++, 'As' => 'Data Provider Candidate', 'Provider' => $this->CompileFunctionName( $Function ) );
				$this->DataCandidates['Providers'][$EventName][$SubscriberID] = $Function;
			}
			return $SubscriberID;
		}

		
		public function UnsubscribeDataProvider( $EventName, $Function )
		{
			foreach ( $this->EventTable[$EventName]['DataProviders'] as $v => $Provider )
			{
				$unset = false;
				if( is_array( $Provider ) && is_array( $Function ) && get_class( $Provider[0] ) == $Function[0] && $Provider[1] == $Function[1] )
				{
					$unset = true;
				}
				elseif( !is_array( $Function ) && $Provider == $Function )
				{
					$unset = true;
				}
				if( $unset )
				{
					$this->EventLog[$EventName][] = array( 'Unsubscribe', 'Time' => microtime( true ), 'EventNumber' => $this->EventNumber++, 'As' => 'Data Provider', 'Provider' => $Function . '()' );
					unset( $this->EventTable[$EventName]['DataProviders'][$v] );
				}
			}
		}
		
		/**
		 * Подписка как приемник данных
		 * @param string $EventName - имя события
		 * @param mixed $Function - экземпляр класса с указанием метода который будет вызываться
		 */
		public function SubscribeAsDataReceiver( $EventName, $Function, $SubscriberID = null )
		{
			if( !$SubscriberID ) $SubscriberID = $this->SubscriberUniqueID++;
			if( $this->IsEventRegister( $EventName ) )
			{
				$this->EventLog[$EventName][] = array( 'Subscribe', 'Time' => microtime( true ), 'EventNumber' => $this->EventNumber++, 'As' => 'Data Receiver', 'Receiver' => $this->CompileFunctionName( $Function ) );
				$this->EventTable[$EventName]['DataReceivers'][$SubscriberID] = $Function;
			}
			else 
			{
				$this->EventLog[$EventName][] = array( 'Subscribe', 'Time' => microtime( true ), 'EventNumber' => $this->EventNumber++, 'As' => 'Data Receiver Candidate', 'Receiver' => $this->CompileFunctionName( $Function ) );
				$this->DataCandidates['Receivers'][$EventName][$SubscriberID] = $Function;
			}
			return $SubscriberID;
		}

		/**
		 * Исполнить событие
		 * @param string $EventName - имя события
		 */
		function FireEvent( $EventName )
		{
			$this->EventLog[$EventName][] = array( 'Fire', 'Time' => microtime( true ), 'EventNumber' => $this->EventNumber++ );

			$DataProviders = &$this->EventTable[$EventName]['DataProviders'];
			$DataReceivers = &$this->EventTable[$EventName]['DataReceivers'];
			$EasySubscribers = &$this->EventTable[$EventName]['EasySubscribers'];

			if( count( $DataReceivers ) )
			{
				reset( $DataProviders );
				while( !is_null( key( $DataProviders ) ) )
				{
					$DataProvider = $DataProviders[key( $DataProviders )];
					$Log = array( 'Call', 'Time' => microtime( true ), 'EventNumber' => $this->EventNumber++, 'DataProvider' => $this->CompileFunctionName( $DataProvider ) );
					$DataProviderResult = call_user_func( $DataProvider, key( $DataProviders ) );
					$Log['EndTime'] = microtime( true );
					$this->EventLog[$EventName][] = $Log;
					reset( $DataReceivers );
					while( !is_null( key( $DataReceivers ) ) )
					{
						$DataReceiver = $DataReceivers[key( $DataReceivers )];
						$Log = array( 'Call', 'Time' => microtime( true ), 'EventNumber' => $this->EventNumber++, 'DataReceiver' => $this->CompileFunctionName( $DataReceiver ) );
						call_user_func_array( $DataReceiver, array( $DataProviderResult, key( $DataReceivers ) ) );
						$Log['EndTime'] = microtime( true );
						$this->EventLog[$EventName][] = $Log;
						next( $DataReceivers );
					}				
					next( $DataProviders );
				}
			}
						
			reset( $EasySubscribers );
			while( !is_null( key( $EasySubscribers ) ) )
			{
				$EasySubscriber = $EasySubscribers[key( $EasySubscribers )];
				$Log = array( 'Call', 'Time' => microtime( true ), 'EventNumber' => $this->EventNumber++, 'Easy' => $this->CompileFunctionName( $EasySubscriber ) );
				call_user_func( $EasySubscriber, key( $EasySubscribers ) );
				$Log['EndTime'] = microtime( true );
				$this->EventLog[$EventName][] = $Log;
				next( $EasySubscribers );
			}			

			$this->EventLog[$EventName][] = array( 'Fired', 'Time' => microtime( true ), 'EventNumber' => $this->EventNumber++ );
			$this->EventTable[$EventName]['Fired'] = true;
		}

		/**
		 * Запуск всех событий на исполнение
		 */
		function FireEvents()
		{
			$this->EventLog['StartFireEvents'] = microtime( true );

			reset( $this->EventTable );
			while( !is_null( key( $this->EventTable ) ) )
			{
				if( !$this->EventTable[key( $this->EventTable )]['Fired'] )
				{
					$this->FireEvent( key( $this->EventTable ) );
				}
				next( $this->EventTable );
			}

			$this->EventLog['EndFireEvents'] = microtime( true );
		}
		
		/**
		 * Компиляция имени класса и функции
		 * Вспомогательная функция для логов
		 * @example передали экземпляр класса Session и метод OnStart, результат выполнения будет "Session->OnStart"
		 * @param mixed $Function
		 * @return string
		 */
		function CompileFunctionName( $Function )
		{
			if( is_array( $Function ) ) return get_class( $Function[0] ) . '->' . $Function[1] . '()';
			else return $Function;
		}
		
		function GetLog()
		{
			$StartTime = array();
			$Result = array();
			foreach ( $this->EventLog as $Event => $EventLog )
			{
				if( !is_array( $EventLog ) ) continue;
				foreach ( $EventLog as $Log )
				{
					switch( $Log[0] )
					{
						case 'Fire':
							$Result[$Log['EventNumber']] = $Log[0] . ' ' . $Event;
							$StartTime[$Event] = $Log['Time'];
						break;
						case 'Call':
							if( array_key_exists( 'Easy', $Log ) )
							{
								$Result[$Log['EventNumber']] = $Log[0] . ' easy ' . $Log['Easy'];
							}
							if( array_key_exists( 'DataProvider', $Log ) )
							{
								$Result[$Log['EventNumber']] = $Log[0] . ' data provider ' . $Log['DataProvider'];
							}
							if( array_key_exists( 'DataReceiver', $Log ) )
							{
								$Result[$Log['EventNumber']] = $Log[0] . ' data receiver ' . $Log['DataReceiver'];
							}
							$Result[$Log['EventNumber']] .= ' for ' . ceil( ($Log['EndTime'] - $Log['Time'] )*100 ) . ' ms';
						break;
						case 'Fired':
							$Result[$Log['EventNumber']] = $Log[0] . ' ' . $Event . ' for ' . ceil( ( $Log['Time'] - $StartTime[$Event] )*100) . ' ms';
						break;
					}
				}
			}
			ksort( $Result );
			
			$CountFire = 0;
			foreach( $Result as &$Log )
			{
				if( strpos( $Log, 'Fired' ) === 0 )
				{
					$CountFire--;
					$Log = str_repeat( "\t", $CountFire ) . $Log;
					$CountFire--;
				}
				elseif( strpos( $Log, 'Fire' ) === 0 )
				{
					$CountFire++;
					$Log = str_repeat( "\t", $CountFire ) . $Log;
					$CountFire++;
				}
				else 
				{
					$Log = str_repeat( "\t", $CountFire ) . $Log;
				}
			}
			return join( "\n", $Result );
		}
	}
?>
