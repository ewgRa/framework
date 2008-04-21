<?php
	class EngineDispatcher
	{
		/**
		 * Отработал ли движок как положено до конца, или где-то ошибка возникла
		 * @var boolean
		 */
		var $FiredStatus = false;
		
		var $AlreadyStartEasy = false;
		
		function __construct()
		{
			$this->NormalizeData();

			EventDispatcher::RegisterCatcher( 'AccessToPageGranted',  array( $this, 'OnAccessToPageGranted' ) );
			
			Registry::Set( 'EngineDispatcher', $this );
		}


		/**
		 * Функция, которая загружает основные сущности, необходимые для "зажигания"
		 */
		function Start()
		{
			register_shutdown_function( array( $this, 'Shutdown' ) );

			# Устанавливаем необходимые обработчики исключений
			$ExceptionMap = Config::getOption( 'Exception Map' );
			if( is_array( $ExceptionMap ) )
			{
				foreach( $ExceptionMap as $ExceptionAlias => $ExceptionClassName )
				{
					ExceptionMap::set( $ExceptionAlias, $ExceptionClassName );
				}
			}
//			set_exception_handler( array( 'ExceptionHandler', 'Handler' ) );


			# Объект сессии
			if( !$this->AlreadyStartEasy ) $Session = new EngineSession();
			
			# Объект посетитель
			$User = new EngineUser();

			# Объект распознает какой язык предпочел пользователь и нормализует входные данные с учетом этого языка
			$Localizer = new EngineLocalizer();

			# Объект распознает какую страницу пользователь загружает
			$Page = new EnginePage();
			
			# Включаем debug mode если это необходимо
			if( array_key_exists( 'debug_server', $_GET ) && !$this->AlreadyStartEasy )
			{
				$Session->Start(); $Session->Data['debug_server'] = true; $Session->Save();
			}
			
			if( !$this->AlreadyStartEasy ) $Session->RelativeStart();
			if( !$this->AlreadyStartEasy && array_key_exists( 'debug_server', $Session->Data ) && $Session->Data['debug_server'] ) Config::setOption( 'Debug mode', true );
			$this->AlreadyStartEasy = true;
		}
		
		/**
		 * Событие срабатывающее при успешном доступе к странице
		 */
		function OnAccessToPageGranted()
		{
			# Определяем какой вид DataCollector'а и View необходимо инициализировать
			$Page = Registry::Get( 'Page' );

			switch( $Page->GetViewType() )
			{
				case 'Redirect':
					EventDispatcher::ClearEventCatchers( 'DataRequested' );
					$DataCollector = DataCollector::Make( 'Redirect' );
					$View = View::Make( 'Redirect' );
				break;	
				case 'XSLT':
					$DataCollector = DataCollector::Make( 'XSLT' );
					$View = View::Make( 'XSLT' );
				break;				
				case 'AJAX':
					$DataCollector = DataCollector::Make( 'AJAX' );
					$View = View::Make( 'AJAX' );
				break;				
				case 'JSON':
					$DataCollector = DataCollector::Make( 'JSON' );
					$View = View::Make( 'JSON' );
				break;
				case 'Native':
					$DataCollector = DataCollector::Make( 'Native' );
					$View = View::Make( 'Native' );
				break;
				case 'Excel':
					$DataCollector = DataCollector::Make( 'Excel' );
					$View = View::Make( 'Excel' );
				break;				
			}


			Registry::Set( 'View', $View );

						
			# Загружаем модули
			$ModuleDispatcher = new ModuleDispatcher();
			$ModuleDispatcher->LoadModules();

			
			EventDispatcher::ThrowEvent( 'RequestData' );
			EventDispatcher::ThrowEvent( 'View', $DataCollector->GetData() );
		}
		
		/**
		 * Отжыг :)
		 */
		function Fire()
		{
			$this->Start();
			EventDispatcher::ThrowEvent( 'EngineStarted' );
			$this->FiredStatus = true;
		}

		/**
		 * Нормализуем входные данные чтобы не зависеть от настроек сервера
		 */
		function NormalizeData()
		{
			if( function_exists( 'set_magic_quotes_runtime' ) )
			{
				set_magic_quotes_runtime( 0 );
			}
			
			if ( function_exists( 'get_magic_quotes_gpc' ) && get_magic_quotes_gpc() )
			{
				$this->strips( $_GET );
				$this->strips( $_POST );
				$this->strips( $_COOKIE );
				$this->strips( $_REQUEST );
				if (isset( $_SERVER['PHP_AUTH_USER'] ) ) $this->strips( $_SERVER['PHP_AUTH_USER'] );
				if (isset( $_SERVER['PHP_AUTH_PW'] ) )   $this->strips( $_SERVER['PHP_AUTH_PW'] );
			}
		}
		
		/**
		 * Функция убирает лишнее экранирование слешей
		 * @param mixed $el
		 */
		function strips( &$el )
		{
			if ( is_array( $el ) )
			{
				foreach( $el as $k => $v ) $this->strips( $el[$k] );
			}
			else $el = stripslashes( $el );
		}
		
		
		/**
		 * Завершение работы движка и окончательный вывод данных
		 */
		function Shutdown()
		{
			$EngineEcho = ob_get_contents();
			ob_clean();
			if( Config::getOption( 'Debug mode' ) )
			{
				$Debug = new Debug();
				$Debug->Set( $_SERVER, 'Server' );
				if( defined( 'END_TIME' ) && defined( 'START_TIME' ) )
				{
					$timing = END_TIME - START_TIME;
				}
				else 
				{
					$timing = '-';
				}
				$Debug->Set( $timing, 'GenerationTime' );
				$Debug->Set( $EngineEcho, 'EngineEcho' );

				# Лог-данные по БД
				$Debug->DataReceiver( $this->DebugDBProvider() );
				
				#События
				$this->EventsLog = EventDispatcher::GetLog();
				$Debug->DataReceiver( $this->DebugEventsProvider() );

				EventDispatcher::RegisterCatcher( 'DebugDataProvide', array( $Debug, 'DataReceiver' ) );
				EventDispatcher::ThrowEvent( 'DebugDataRequested' );
				
				$Session = Registry::Get( 'Session' );
				if( !array_key_exists( 'Debug', $Session->Data ) ) $Session->Data['Debug'] = array();
				$Session->Data['Debug'][] = $Debug->GetData();
				$Session->Save();
			}
			
			if( $this->FiredStatus )
			{
				$View = Registry::Get( 'View', false );
				$View->Shutdown();
			}
			else
			{
				echo $EngineEcho;
			}
			exit();
		}
		
		
		function DebugDBProvider()
		{
			$DB = Registry::Get( 'DB' );
			return array( 'Data' => $DB->Log, 'Prefix' => 'DB' );
		}

		
		function DebugEventsProvider()
		{
			return array( 'Data' => $this->EventsLog, 'Prefix' => 'Events' );
		}

		
		function ForwardToURI( $URI )
		{
			$_SERVER['REQUEST_URI'] = $URI;


			EventDispatcher::ClearAllCatchers();
			EventDispatcher::RegisterCatcher( 'AccessToPageGranted',  array( $this, 'OnAccessToPageGranted' ) );
			
			Registry::Set( 'EngineDispatcher', $this );
			$this->Fire();
		}
	}
?>
