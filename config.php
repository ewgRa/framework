<?php
	include_once( LIB_DIR . '/php/spyc/spyc.php' );

	/**
	 * Класс, реализующий операции с настройками сайта, переменных и т.п.
	 */
	class Config
	{
		public static $ConfigFile = null;
		public static $ConfigCacheFile = null;
		public static $BuildType = null;

		/**
		 * Переменная хранит различные опции для сайта
		 * @var array
		 */
		private static $Options = array();

		/**
		 * Флаг, который показывает что мы уже загрузили опции
		 * @var boolean
		 */
		private static $AlreadyLoadOptions = false;


		public static function Initialize( $YAML_file, $build_type, $cache_file = null )
		{
			self::$ConfigFile = $YAML_file;
			self::$ConfigCacheFile = $cache_file;
			self::$BuildType = $build_type;
			if( $cache_file && file_exists( $cache_file ) && filemtime( $YAML_file ) == filemtime( $cache_file ) )
			{
				self::$Options = unserialize( file_get_contents( $cache_file ) );
			}
			else
			{
				$SettingsAll = Spyc::YAMLLoad( $YAML_file );
				# Сливаем секцию all и ту что нам указали
				$Settings = array();
				if( array_key_exists( $build_type, $SettingsAll ) && is_array( $SettingsAll[$build_type] ) )
				{
					$Settings = $SettingsAll[$build_type];
				}
				if( array_key_exists( 'all', $SettingsAll ) )
				{
					$Settings = self::array_merge_recursive2( $SettingsAll['all'], $Settings );
				}
				
				foreach( $Settings as $k => $v )
				{
					$Settings[$k] = self::ReplaceVars( $v );
					self::setOption( $k, self::ReplaceVars( $v ) );
				}

				if( $cache_file )
				{
					file_put_contents( $cache_file, serialize( self::$Options ) );
					touch( $cache_file, filemtime( $YAML_file ) );
				}
			}

			if( array_key_exists( 'constants', self::$Options ) )
			{
				self::RegisterConstants( self::$Options['constants'] );
			}
			
			if( array_key_exists( 'cookie_domain', self::$Options ) )
			{
				$cookie_params = session_get_cookie_params();
				session_set_cookie_params ( $cookie_params['lifetime'], $cookie_params['path'], self::$Options['cookie_domain'], $cookie_params['secure'] );
			}
			else
			{
				$cookie_params = session_get_cookie_params();
				$Domain = preg_replace( '/^www./i', '', $_SERVER['HTTP_HOST'] );
				self::$Options['cookie_domain'] = $Domain;
				session_set_cookie_params ( $cookie_params['lifetime'], $cookie_params['path'], $Domain, $cookie_params['secure'] );
			}
		}

		public function RegisterConstants( $ConstatsArray )
		{
			foreach( $ConstatsArray as $k => &$constant_value )
			{
				$constant_value = self::ReplaceVars( $constant_value );
				define( $k, $constant_value );
			}			
		}
		
		public static function ReplaceVars( $Var )
		{
			if( is_array( $Var ) )
			{
				foreach( $Var as $k => $v )
				{
					$Var[$k] = self::ReplaceVars( $v );
				}
			}
			else
			{
				preg_match_all( '/%(.*?)%/', $Var, $matches );
				foreach( array_unique( $matches[1] ) as $match )
				{
					eval( '$Var = str_replace( "%" . $match . "%", ' . $match . ', $Var );' );
				}
			}

			return $Var;
		}

		/**
		 * Функция загрузки опций из БД
		 */
        public static function LoadOptions()
        {
            self::$AlreadyLoadOptions = true;
        	$Options = Cache::Get( 'Load Options', 'engine/config' );
			if( Cache::Expired() )
			{
			    	$DB = Registry::Get( 'DB' );
			        $dbq = "SELECT * FROM " . $DB->TABLES['Options'];
			    	$Options = array();
			        $dbr = $DB->Query( $dbq );
			        while( $db_row = $DB->FetchArray( $dbr ) )
			        {
						$Options[$db_row['alias']] = $db_row['value'];
			        }
			        Cache::Set( $Options, 24*60*60 );
			}
            self::$Options = array_merge( self::$Options, $Options );
        }

        /**
         * Функция возвращает значение опции по алиасу
		 * @param string $Alias - алиас опции
		 * @return mixed - значение опции
		 */
		public static function getOption( $Alias )
		{
			if( array_key_exists( $Alias, self::$Options ) )
			{
				return self::$Options[$Alias];
			}
			elseif( !self::$AlreadyLoadOptions )
			{
				self::LoadOptions();
				return self::getOption( $Alias );
			}
			else return null;
		}

		/**
         * Функция устанавливает значение опции по алиасу
		 * @param string $Alias - алиас опции
		 * @param mixed $Value - значение опции
		 */
		public static function setOption( $Alias, $Value )
		{
			self::$Options[$Alias] = $Value;
		}

		/**
		 * http://ru2.php.net/manual/ru/function.array-merge-recursive.php#42663
		 */
		public static function array_merge_recursive2( $paArray1, $paArray2 )
		{
		    if( !is_array( $paArray1 ) || !is_array( $paArray2 ) )
		    {
		    	return $paArray2;
		    }
		    foreach( $paArray2 AS $sKey2 => $sValue2 )
		    {
		        $paArray1[$sKey2] = self::array_merge_recursive2( @$paArray1[$sKey2], $sValue2 );
		    }
		    return $paArray1;
		}
	}
?>
