<?php
	/* $Id$ */
	
	// FIXME: tested?
	class EngineDispatcher extends Singleton
	{
		const OPTIONS_CACHE_LIFE_TIME = 86400;
		
		private $fired = false;
		
		/**
		 * @return EngineDispatcher
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
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

		public function setFired()
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
			$result = array();
			
			$cacheTicket = Cache::me()->createTicket()->
				setPrefix('options')->
				setKey(__CLASS__, __FUNCTION__)->
				setActualTime(time() + self::OPTIONS_CACHE_LIFE_TIME)->
				restoreData();
			
			if($cacheTicket->isExpired())
			{
				$dbQuery = "SELECT * FROM " . Database::me()->getTable('Options');
		        $dbResult = Database::me()->query($dbQuery);
				
				while($dbRow = Database::me()->fetchArray($dbResult))
					$result[$dbRow['alias']] = $dbRow['value'];

				$cacheTicket->setData($result)->storeData();
			}
			else
				$result = $cacheTicket->getData();
            
			return $result;
		}
		
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
			
			if(Session::me()->isStarted())
				User::me()->onSessionStarted();
			
			foreach($this->loadSiteOptions() as $option => $value)
				Config::me()->setOption($option, $value);
			
			Localizer::me()->defineLanguage();
			
			$cacheTicket = Cache::me()->createTicket()->
				setPrefix('pagepathmapper')->
				setKey('pagepathmapper')->
				setActualTime(time() + PagePathMapper::CACHE_LIFE_TIME)->
				restoreData();
			
			if($cacheTicket->isExpired())
			{
				PagePathMapper::me()->loadMap();
				$cacheTicket->setData(PagePathMapper::me())->storeData();
			}
			else
				Singleton::setInstance('PagePathMapper', $cacheTicket->getData());
			
			$pageId = PagePathMapper::me()->getPageId(
				UrlHelper::me()->getEnginePagePath()
			);

			$cacheTicket = Cache::me()->createTicket()->
				setPrefix('page')->
				setKey(
					$pageId ? $pageId : UrlHelper::me()->getEnginePagePath()
				)->
				setActualTime(time() + Page::CACHE_LIFE_TIME)->
				restoreData();
			
			if($cacheTicket->isExpired())
			{
				Page::me()->loadPage(UrlHelper::me()->getEnginePagePath(), $pageId);
				$cacheTicket->setData(Page::me())->storeData();
			}
			else
				Singleton::setInstance('Page', $cacheTicket->getData());

			Page::me()->
				setRequestPath(UrlHelper::me()->getEnginePagePath())->
				processPath();
			
			if(Page::me()->getViewType() == View::AJAX)
				JsHttpRequest::initialize(Config::me()->getOption('charset'));
			
			Page::me()->checkAccessPage(User::me()->getRights());
			
			var_dump(Page::me());
			var_dump(Localizer::me());
			die;

//			EventDispatcher::ThrowEvent( 'EngineStarted' );
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
