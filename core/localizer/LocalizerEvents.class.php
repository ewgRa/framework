<?php
	/* $Id$ */

	class EngineLocalizer extends Localizer
	{
		public function __construct()
		{
			parent::__construct();
			Registry::Set( 'Localizer', $this );
			EventDispatcher::RegisterCatcher( 'EngineStarted', array( $this, 'DefineLanguage' ) );
			EventDispatcher::RegisterCatcher( 'DataRequested', array( $this, 'DataRequested' ) );
		}

		public function DataRequested()
		{
			EventDispatcher::ThrowEvent( 'DataProvide', $this->DataProvider() );
		}
		
		
		public function DataProvider()
		{
			return array( 'Data' => $this->Language, 'Prefix' => array( 'LOCALIZER' ) );
		}

		public function DefineLanguage()
		{
			if( parent::DefineLanguage( $_SERVER['REQUEST_URI'] ) )
			{
				$Session = Registry::Get( 'Session' );
				$Session->SetCookie( 'language', $this->Language['abbr'], 30 * 24 * 60 * 60, '/', Config::getOption( 'cookie_domain' ) );
				$Session->SetCookie( 'language_id', $this->Language['id'], 30 * 24 * 60 * 60, '/', Config::getOption( 'cookie_domain' ) );
	
				$_SERVER['REQUEST_URI'] = $this->GetNormalizeURL();
	
				$_SERVER['URI'] = array_shift( explode( '?', $_SERVER['REQUEST_URI'] ) );
	
				EventDispatcher::ThrowEvent( 'LanguageDefined' );
			}
		}
	}


	/**
	 * Базовых класс отвечающий за определение языка и нормализацию входящих данных
	 */
	class Localizer
	{
		# Константы, определяющие что послужило источником для принятия решения о выборе языка
		const SOURCE_LANGUAGE_DEFAULT = 1; # Источником стало решение выбрать язык по умолчанию
		const SOURCE_LANGUAGE_COOKIE = 2; # Источником стала информация, изъятая из Cookie
		const SOURCE_LANGUAGE_URL = 3; # Источником стала информация, хранящаяся в URL
		const SOURCE_LANGUAGE_URL_AND_COOKIE = 4; # Источником стала информация, хранящаяся в URL, также указание языка было обнаружено в COOKIE

		/**
		 * Массив, хранящий параметры языка
		 * @var array
		 */
		var $Language = array();

		/**
		 * Источник, использованый для принятия решения о выборе языка
		 * @var int
		 */
		var $Source;

		function __construct()
		{
			$this->Source = self::SOURCE_LANGUAGE_DEFAULT;
		}

		/**
		 * Функция возвращает Alias языка
		 * @return string
		 */
		function GetLanguage()
		{
			return $this->Language['abbr'];
		}

		/**
		 * Функция возвращает ID языка
		 * @return int
		 */
		function GetLanguageID()
		{
			return $this->Language['id'];
		}

		/**
		 * Составляющая языка, которую возможно необходимо включать в URL при формировании ссылок
		 * @return string
		 */
		function GetLanguageURL()
		{
			switch ( $this->Source )
			{
				case self::SOURCE_LANGUAGE_URL_AND_COOKIE:
					return '';
				break;
				case self::SOURCE_LANGUAGE_COOKIE:
					return '';
				break;
				case self::SOURCE_LANGUAGE_DEFAULT:
					return '/' . $this->Language['abbr'];
				break;
				case self::SOURCE_LANGUAGE_URL:
					return '/' . $this->Language['abbr'];
				break;
			}
		}

		/**
		 * Определение языка
		 * Смотрим URL, если там указан язык: Source = SOURCE_LANGUAGE_URL, проверяем, есть ли что-то в куках по языку, если да то SOURCE = SOURCE_LANGUAGE_URL_AND_COOKIE, пишем в куки определенный язык
		 * Если не нашли указание в URL - смотрим куки, если там находим: Source = SOURCE_LANGUAGE_COOKIE
		 * Если не нашли в куках: Source = SOURCE_LANGUAGE_DEFAULT
		 * К ссылкам добавляем часть, которая отвечает за язык в случае если Source = SOURCE_LANGUAGE_URL и SOURCE_LANGUAGE_DEFAULT
		 * @return boolean
		 */
		public function DefineLanguage( $URI )
		{
			if( array_key_exists( 'language' , $_COOKIE ) && array_key_exists( 'language_id' , $_COOKIE ) )
			{
				$this->Language['abbr'] = $_COOKIE['language'];
				$this->Language['id'] = $_COOKIE['language_id'];
				$this->Source = self::SOURCE_LANGUAGE_COOKIE;
			}

			$parsed_url = parse_url( $URI );
			$probable_lang = array_shift( explode( "/", substr( $parsed_url['path'], 1 ) ) );

			$lang_array = $this->GetLanguages();
			if( in_array( $probable_lang, $lang_array ) )
			{
				$this->Language['abbr'] = $probable_lang;
				$flip_lang_array = array_flip( $lang_array );
				$this->Language['id'] = $flip_lang_array[$probable_lang];
				if( $this->Source == self::SOURCE_LANGUAGE_COOKIE )
				{
					$this->Source = self::SOURCE_LANGUAGE_URL_AND_COOKIE;
				}
				else $this->Source = self::SOURCE_LANGUAGE_URL;
			}

			if( $this->Source == self::SOURCE_LANGUAGE_DEFAULT )
			{
				$this->SetDefaultLanguage();
			}

			$this->Language['language_url'] = $this->GetLanguageURL();

			return true;
		}

		/**
		 * Функция загрузки списка языков и БД
		 * @return array
		 */
		function GetLanguages()
		{
        	$lang_array = Cache::Get( 'Get Languages', 'engine/localizer' );
			if( Cache::Expired() )
			{
				$DB = Registry::Get( 'DB' );
				$dbq = "SELECT * FROM " . $DB->TABLES['Languages'];
				$lang_array = array();
				$dbr = $DB->Query( $dbq );
				while( $db_row = $DB->FetchArray( $dbr ) )
				{
					$lang_array[$db_row['id']] = $db_row['abbr'];
				}
		        Cache::Set( $lang_array, 24*60*60 );
			}
			return $lang_array;
		}

		/**
		 * Установить язык по умолчанию ( не в БД, а для этого конкретного посетителя )
		 * @return boolean
		 */
		function SetDefaultLanguage()
		{
        	$this->Language = Cache::Get( 'Set Default Language', 'engine/localizer' );
			if( Cache::Expired() )
			{
				$DB = Registry::Get( 'DB' );
				$dbq = "SELECT t1.* FROM " . $DB->TABLES['Languages'] . " t1 INNER JOIN " . $DB->TABLES['Options'] . " t2 ON ( t2.alias = 'DefaultLanguage' AND t2.value = t1.id )";
				$this->Language = array();
				$dbr = $DB->Query( $dbq );
				if( $DB->RecordCount( $dbr ) )
				{
					$db_row = $DB->FetchArray( $dbr );
					$this->Language['abbr'] = $db_row['abbr'];
					$this->Language['id'] = $db_row['id'];
				}
		        Cache::Set( $this->Language, 24*60*60 );
			}
			return true;
		}

		/**
		 * Получить URL без составляющей языка
		 * @return string
		 */
		public function GetNormalizeURL()
		{
			$NormalizeURL = $_SERVER['REQUEST_URI'];
			$parsed_url = parse_url( $_SERVER['REQUEST_URI'] );
			if( in_array( $this->Source, array( self::SOURCE_LANGUAGE_URL, self::SOURCE_LANGUAGE_URL_AND_COOKIE ) ) )
			{
				$parsed_url['path'] = substr( $parsed_url['path'], 1 );
				$path = explode( '/', $parsed_url['path'] );
				array_shift( $path );
				$NormalizeURL = '/' . join( '/', $path );
				if( !empty( $_SERVER['QUERY_STRING'] ) ) $NormalizeURL .= '?' . $_SERVER['QUERY_STRING'];
			}
			return $NormalizeURL;
		}
	}
?>
