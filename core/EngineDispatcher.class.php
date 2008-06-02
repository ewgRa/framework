<?php
	// FIXME: tested?
	class EngineDispatcher extends Singleton
	{
		const OPTIONS_CACHE_LIFE_TIME = 86400;
		
		private $fired = false;
		
		private static $instance = null;
		
		/**
		 * @return EngineDispatcher
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__, self::$instance);
		}
		
		// FIXME: refatoring?
		protected function __construct()
		{
//			EventDispatcher::RegisterCatcher( 'AccessToPageGranted',  array( $this, 'OnAccessToPageGranted' ) );
		}

		public function normalizeRequest()
		{
			if(function_exists('set_magic_quotes_runtime'))
			{
				set_magic_quotes_runtime(0);
			}
			
			if(function_exists( 'get_magic_quotes_gpc') && get_magic_quotes_gpc())
			{
				$this->
					strips($_GET)->
					strips($_POST)->
					strips($_COOKIE)->
					strips($_REQUEST);
				
				if(isset($_SERVER['PHP_AUTH_USER']))
					$this->strips($_SERVER['PHP_AUTH_USER']);

				if(isset($_SERVER['PHP_AUTH_PW']))
					$this->strips($_SERVER['PHP_AUTH_PW']);
			}
			
			return $this;
		}

		public function fired()
		{
			$this->fired = true;
			return $this;
		}

		public function isFired()
		{
			return $this->fired;
		}
		
		public function loadSiteOptions()
		{
			$options = Cache::me()->get(
				array(__CLASS__, __FUNCTION__),
				'site/options'
			);

			if(Cache::me()->isExpired())
			{
				$options = array();
				$dbQuery = "SELECT * FROM " . Database::me()->getTable('Options');
				
		        $dbResult = Database::me()->query($dbQuery);
				
				while($dbRow = Database::me()->fetchArray($dbResult))
				{
					$options[$dbRow['alias']] = $dbRow['value'];
		        }

				Cache::me()->set($options, self::OPTIONS_CACHE_LIFE_TIME);
			}
            
			return $options;			
		}
		
		// FIXME: refatoring?
		public function start()
		{
			register_shutdown_function(array($this, 'shutdown'));

			$exceptionMap = Config::me()->getOption('exceptionMap');
			
			if($exceptionMap)
			{
				foreach($exceptionMap as $exceptionAlias => $exceptionClassName)
				{
					ExceptionsMapper::me()->setClassName(
						$exceptionAlias, $exceptionClassName
					);
				}
			}
			
			Session::me()->relativeStart();
			
			if(Session::get('userId'))
			{
				User::me()->setUserId(Session::get('userId'));
			}
			
			foreach($this->loadSiteOptions() as $option => $value)
			{
				Config::me()->setOption($option, $value);
			}
			
			var_dump(Config::me());
			die;

/*			return $this;
			
			# Объект посетитель
			$User = new EngineUser();

			# Объект распознает какой язык предпочел пользователь и нормализует входные данные с учетом этого языка
			$Localizer = new EngineLocalizer();

			# Объект распознает какую страницу пользователь загружает
			$Page = new EnginePage();
			
*/
			EventDispatcher::ThrowEvent( 'EngineStarted' );
		}
		
		// FIXME: refatoring?
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
		
		// FIXME: refatoring?
		/**
		 * Завершение работы движка и окончательный вывод данных
		 */
		function Shutdown()
		{
/*			$EngineEcho = ob_get_contents();
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
*/
		}
		
		// FIXME: refatoring?
		function ForwardToURI( $URI )
		{
			$_SERVER['REQUEST_URI'] = $URI;


			EventDispatcher::ClearAllCatchers();
			EventDispatcher::RegisterCatcher( 'AccessToPageGranted',  array( $this, 'OnAccessToPageGranted' ) );
			
			Registry::Set( 'EngineDispatcher', $this );
			$this->Fire();
		}
		
		private function strips(&$el)
		{
			if(is_array($el))
			{
				foreach($el as &$v)
					$this->strips($v);
			}
			else
				$el = stripslashes($el);
				
			return $this;
		}
	}
?>
