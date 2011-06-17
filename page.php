<?php
	/**
	 * Класс-контроллер, отвечает за определение какую страницу пользователь хочет загрузить
	 */
	class EnginePage extends Page
	{
		public function __construct()
		{
			Registry::Set( 'Page', $this );

			EventDispatcher::RegisterCatcher( 'LanguageDefined', array( $this, 'OnLanguageDefined' ) );
			EventDispatcher::RegisterCatcher( 'UserRightsLoaded', array( $this, 'LoadUserRightsReceiver' ) );
			
			EventDispatcher::RegisterCatcher( 'ViewSettingsRequested', array( $this, 'ViewSettingsRequested' ) );
			EventDispatcher::RegisterCatcher( 'DataRequested', array( $this, 'DataRequested' ) );

			EventDispatcher::RegisterCatcher( 'LayoutFileRequested', array( $this, 'LayoutFileRequested' ) );
		}
		
		public function LayoutFileRequested()
		{
			EventDispatcher::ThrowEvent( 'LayoutFileProvide', $this->GetLayoutFileProvider() );
		}
		
		public function ViewSettingsRequested()
		{
			EventDispatcher::ThrowEvent( 'ViewSettingsProvide', $this->ViewSettingsProvider() );
		}

		public function DataRequested()
		{
			EventDispatcher::ThrowEvent( 'DataProvide', $this->DataProvider() );
			EventDispatcher::ThrowEvent( 'DataProvide', $this->DictionaryDataProvider() );
		}

		
		/**
		 * Подписываемся на сбор данных как источник
		 * @return array
		 */
		public function DataProvider()
		{
			return array( 'Data' => $this->Page, 'Prefix' => array( 'PAGE' ) );
		}
		
		
		/**
		 * Подписываемся на сбор данных как источник
		 * @return array
		 */
		public function DictionaryDataProvider()
		{
			return array( 'Data' => $this->Dictionary, 'Prefix' => array( 'DICTIONARY' ) );
		}
		
		/**
		 * Подписываемся примеником на событие загрузки прав пользователя (OnLoadUserRights) чтобы проверить имеет ли он на право доступа к этой странице
		 * @param array $Rights - права пользователя
		 */
		public function LoadUserRightsReceiver( $Rights )
		{
			if( $this->CheckUserRights( $Rights ) )
			{
				EventDispatcher::ThrowEvent( 'AccessToPageGranted' );
			}
		}

		/**
		 * Подписываемся простой выполняющей функцией на событие определния языка OnLanguageDefined (OnLoadUserRights) чтобы проверить имеет ли он на право доступа к этой странице
		 */
		public function OnLanguageDefined()
		{
			if( $this->DefinePage() )
			{
				EventDispatcher::ThrowEvent( 'PageDefined' );
			}
		}
	}
	
	/**
	 * Класс, отвечающий за определение какую страницу пользователь хочет загрузить
	 */
	class Page
	{
		/**
		 * Настройки которые будут передаваться контроллерам модулей
		 * @see TPageParams
		 * @var array
		 */
		var $ControllerSettings = array();
		
		/**
		 * Настройки модулей для View составляющей
		 * @var array
		 */
		var $ViewSettings = array();

		/**
		 * Массив хранит данные о странице
		 * @var array
		 */
		var $Page = array();

		/**
		 * Получить настройки представления данных модулей
		 * @return array
		 */
		function ViewSettingsProvider()
		{
			return $this->ViewSettings;
		}

		/**
		 * Функция возвращает ID страницы
		 * @return int
		 */
		function GetID()
		{
			return $this->Page['id'];
		}
		
		/**
		 * Функция возвращает тип View, который используется на странице
		 * @return string
		 */
		function GetViewType()
		{
			return $this->Page['view_type'];
		}

		/**
		 * Функция возвращает путь к файлу с шаблоном
		 * @return string
		 */
		function GetLayoutFileProvider()
		{
			return $this->Page['layout_file'];
		}

		
		function GetIncludeFileForDirect( $DirectFiles )
		{
			$DB = Registry::Get( 'DB' );
			# Получаем список файлов, которые подключены к файлам, указанным как напрямую подключенные
			$dbq = 'SELECT t1.include_file_id, t2.path, t2.can_split FROM ' . $DB->TABLES['ViewFilesIncludes'] . ' t1 INNER JOIN ' . $DB->TABLES['ViewFiles'] . ' t2 ON ( t2.id = t1.include_file_id ) WHERE t1.file_id IN ( ? ) ORDER BY t1.position DESC';
			$dbr = $DB->Query( $dbq, array( $DirectFiles ) );
			$DirectFiles = array();

			while( $db_row = $DB->FetchArray( $dbr ) )
			{
				$DirectFiles[] = $db_row['include_file_id'];
				$Extension = array_pop( explode( '.', $db_row['path'] ) );

				if( $Extension == 'xsl' )
				{
					$db_row['path'] = str_replace( '\\', '/', realpath( Config::ReplaceVars( $db_row['path'] ) ) );
				}

				$this->ViewSettings['include_files'] = array_merge( array( $db_row['path'] ), $this->ViewSettings['include_files'] );

				if( $db_row['can_split'] == 'no' )
				{
					$this->ViewSettings['dont_split_files'][] = $db_row['path'];
				}
//				$this->ViewSettings['include_files'][] = $db_row['path'];
			}
			if( count( $DirectFiles ) ) $this->GetIncludeFileForDirect( $DirectFiles );
		}
		
		/**
		 * Определение страницы, которая опубликована на данный URL, если страниц несколько, выбираем одну по какому-нибудь правилу
		 * @return boolean
		 */
		public function DefinePage()
		{
			$Localizer = Registry::Get( 'Localizer' );
			
			$CacheData = Cache::Get( array( 'Define Page', $_SERVER['URI'], $Localizer->GetLanguageID() ), 'engine/page');
			
			if( Cache::Expired() )
			{
				$result = true;
				$DB = Registry::Get( 'DB' );
				$parse_url = parse_url( $_SERVER['URI'] );
				$url = $parse_url['path'];
				$dbq = "SELECT t1.*, t3.id as layout_file_id, t3.path as layout_file, t4.title, t4.description, t4.keywords FROM " . $DB->TABLES['Pages'] . " t1 LEFT JOIN " . $DB->TABLES['Layouts'] . " t2 ON( t2.id = t1.layout_id) LEFT JOIN " . $DB->TABLES['ViewFiles'] . " t3 ON ( t3.id = t2.file_id ) LEFT JOIN " . $DB->TABLES['PagesData'] . " t4 ON( t4.page_id = t1.id AND t4.language_id = ? ) WHERE IF( t1.preg IS NULL, t1.url = ?, ? REGEXP t1.url)";
				$dbr = $DB->Query( $dbq, array( $Localizer->GetLanguageID(), $url, $url ) );
				if( $DB->RecordCount( $dbr ) ) $Pages = $DB->ResourceToArray( $dbr );
				else throw new ExceptionMap::$Classes['PageExceptionClass']( 'Page "' . $url . '" is not defined', PageException::PAGE_NOT_DEFINED );

				if( is_array( $Pages ) )
				{
					foreach($Pages as &$page)
						$page['real_url'] = $url;
					
					usort( $Pages, array( $this, 'SortPages' ) );
					$this->Page = array_shift( $Pages );
					preg_match( "@" . $this->Page['url'] . "@", $url, $this->Page['matches'] );
				}
				
				$this->Page['layout_file'] = Config::ReplaceVars( $this->Page['layout_file'] );
				$this->Page['alias'] = explode( "/", $url );
				$this->Page['uri'] = $_SERVER['URI'];
				
				# Получаем список файлов, которые подключены напрямую к странице
				$this->ViewSettings = array( 'include_files' => array(), 'dont_split_files' => array() );
				$dbq = 'SELECT t2.id, t2.path, t2.can_split, t1.only_this_file FROM ' . $DB->TABLES['PagesViewFiles_ref'] . ' t1 INNER JOIN ' . $DB->TABLES['ViewFiles'] . ' t2 ON( t1.page_id = ? AND t2.id = t1.file_id )';
				$dbr = $DB->Query( $dbq, array( $this->Page['id'] ) );
				$DirectFiles = array();
				while( $db_row = $DB->FetchArray( $dbr ) )
				{
					$Extension = array_pop( explode( '.', $db_row['path'] ) );
					if( $Extension == 'xsl' )
					{
						$db_row['path'] = str_replace( '\\', '/', realpath( Config::ReplaceVars( $db_row['path'] ) ) );
					}
					$this->ViewSettings['include_files'][] = $db_row['path'];
					if( $db_row['can_split'] == 'no' )
					{
						$this->ViewSettings['dont_split_files'][] = $db_row['path'];
					}

					if( !$db_row['only_this_file'] ) $DirectFiles[] = $db_row['id'];
				}
								
				$DirectFiles[] = $this->Page['layout_file_id'];
				if( count( $DirectFiles ) ) $this->GetIncludeFileForDirect( $DirectFiles );
				
				# Выявляем необходимые настройки словаря для View файлов
				$this->Dictionary = array();
				$dbq = "SELECT t3.alias,t4.value, t5.path REGEXP '.*\.js$' as js_file FROM " . $DB->TABLES['PagesViewFiles_ref'] . " t1 INNER JOIN " . $DB->TABLES['ViewFilesDictionaryWords_ref'] . " t2 ON( t2.file_id = t1.file_id ) INNER JOIN " . $DB->TABLES['DictionaryWords'] . " t3 ON( t3.id = t2.word_id ) INNER JOIN " . $DB->TABLES['DictionaryWordsData'] . " t4 ON( t4.word_id = t3.id AND t4.language_id = ? ) INNER JOIN " . $DB->TABLES['ViewFiles'] . " t5 ON( t5.id = t1.file_id ) WHERE t1.page_id = ?";
				$dbr = $DB->Query( $dbq, array( $Localizer->GetLanguageID(), $this->Page['id'] ) );
				while( $db_row = $DB->FetchArray( $dbr ) )
				{
					if( $db_row['js_file'] )
					{
						$this->Dictionary['js'][$db_row['alias']] = $db_row['value'];
					}
					else $this->Dictionary[$db_row['alias']] = $db_row['value'];
				}
				
				$dbq = 'SELECT * FROM ' . $DB->TABLES['PagesParams'] . ' WHERE page_id = ?';
				$dbr = $DB->Query( $dbq, array( $this->Page['id'] ) );
				$this->PageParams = $DB->ResourceToArray( $dbr );
				
				Cache::Set( array( $result, $this->Page, $this->ViewSettings, $this->Dictionary, $this->PageParams ), 24*60*60 );
			}
			else
			{
				$result = $CacheData[0];
				$this->Page = $CacheData[1];
				$this->ViewSettings = $CacheData[2];
				$this->Dictionary = $CacheData[3];
				$this->PageParams = $CacheData[4];
			}
			
			$this->Page['request_uri'] = $_SERVER['REQUEST_URI'];

			if( $this->GetViewType() == 'AJAX' )
			{
				$JsHttpRequest = JsHttpRequest::getInstance( 'UTF-8' );
			}
			
			foreach( $this->PageParams as $db_row )
			{
				switch( $db_row['type'] )
				{
					case 'request_uri':
						$this->ControllerSettings[$db_row['alias']] = array_key_exists( $db_row['value'], $this->Page['matches'] ) ? $this->Page['matches'][$db_row['value']] : $db_row['default'];
					break;
					case '_GET':
						$this->ControllerSettings[$db_row['alias']] = array_key_exists( $db_row['value'], $_GET )? $_GET[$db_row['value']] : $db_row['default'];
					break;
					case '_POST':
						$this->ControllerSettings[$db_row['alias']] = array_key_exists( $db_row['value'], $_POST )? $_POST[$db_row['value']] : $db_row['default'];
					break;
				}
			}

			return $result;
		}

		
		/**
		 * Функция сортировки страниц.
		 * Возможна ситуация, когда на один URL подходит несколько страниц ( такая ситуация возможна при некорректном описании регулярного выражения )
		 * @param array $PageA
		 * @param array $PageB
		 * @return boolean
		 */
		private function SortPages( &$PageA, &$PageB )
		{
			preg_match( "@" . $PageA['url'] . "@", $PageA['real_url'], $matchesA );
			preg_match( "@" . $PageB['url'] . "@", $PageB['real_url'], $matchesB );
			if( ( count( $matchesA ) < count( $matchesB ) && !is_null( $PageA['preg'] ) ) || is_null( $PageB['preg'] ) )
			{
				return 1;
			}
			return -1;
		}
		
		/**
		 * Функция для выяснения, какие права нужны для просмотра данной страницы
		 * @param int $PageID
		 * @return array $Result
		 */
		protected function GetPageRights( $PageID )
		{
			$Result = Cache::Get( array( 'Get Page Rights', $PageID ), 'engine/page' );
			if( Cache::Expired() )
			{
				$DB = Registry::Get( 'DB' );
				$Result = array();
				$dbq = 'SELECT t1.right_id, t2.url as redirect_page FROM ' . $DB->TABLES['PagesRights_ref'] . ' t1 LEFT JOIN ' . $DB->TABLES['Pages'] . ' t2 ON( t1.redirect_page = t2.id ) WHERE t1.page_id = ?';
				$dbr = $DB->Query( $dbq, array( $PageID ) );
				while( $db_row = $DB->FetchArray( $dbr ) )
				{
					$Result[$db_row['right_id']] = $db_row['redirect_page'];
				}
				Cache::Set( $Result, 24*60*60 );
			}
			
			return $Result;
		}
		
		
		/**
		 * Функция проверки, может ли пользователь получить доступ к этой странице
		 * если не может, будет выбрасываться исключение EXCEPTION_NO_RIGHT_FOR_PAGE
		 * @param array $UserRights - список прав пользователя
		 */
		protected function CheckUserRights( $UserRights )
		{
			$PageRights = $this->GetPageRights( $this->Page['id'] );
			if( count( $PageRights ) )
			{
				$IntersectRights = array_intersect( array_keys( $PageRights ), array_keys( $UserRights ) );
				if( !count( $IntersectRights ) )
				{
					$NoRights = array_diff( array_keys( $PageRights ), $IntersectRights );
					throw new ExceptionMap::$Classes['PageExceptionClass'](
						serialize( array(
							'text' => 'No rights for page',
							'redirect_page' => $PageRights[$NoRights[0]],
							'view_type' => $this->Page['view_type'],
							'user_rights' => $UserRights,
							'page_rights' => $PageRights,
							'intersect_rights' => $IntersectRights,
							'no_rights' => $NoRights ) ),
						PageException::NO_RIGHTS );
				}
			}
			return true;
		}
	}
?>
