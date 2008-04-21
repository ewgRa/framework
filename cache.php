<?php
	if( !defined( 'CACHE_DIR' ) ) define( 'CACHE_DIR', 'cache' );	

	/**
	 * Класс кеширования данных и их извлечения из кеша
	 */
	class Cache
	{
		/**
		 * При установке в true кеширование (Set) не происходит
		 * @var boolean
		 */
		public static $Disabled = false;

		/**
		 * Флаг для указания что последняя попытка извлечь кеш была неудачной, кеш был не найден
		 * @var boolean
		 */
		public static $CacheExpired = true;
		
		/**
		 * Директория, где хранится кеш
		 * @var string
		 */
		public static $CacheDir = CACHE_DIR;

		/**
		 * Значение актуальности кеша по умолчанию в секундах
		 * @var int
		 */
		private static $DefaultExpire =  31536000; #one year

		/**
		 * Ключ, который используется для выяснения MD5 хеша
		 * Запоминается в методе Get, чтобы можно было в методе Set потом не указывать
		 * @var string
		 */
		private static $Key;

		/**
		 * Префикс, который используется для формирования имени файла
		 * Запоминается в методе Get, чтобы можно было в методе Set потом не указывать
		 * @var string
		 */
		private static $Prefix;

		/**
		 * Кеш или не был найден, или "протух"
		 * @return boolean
		 */
		public function Expired()
		{
			return self::$CacheExpired;
		}

		/**
		 * Изъять данные из кеша
		 * @param mixed $Key - ключ для вычисления MD5 хеша
		 * @param mixed $Prefix - префикс для формирования файла
		 * @return mixed
		 */
		public function Get( $Key, $Prefix = '' )
		{
			if( self::$Disabled ) return null;

			self::$Key = $Key;
			self::$Prefix = $Prefix;

			$Key = serialize( $Key );

			$FileName = self::GetFileName( $Key, $Prefix );
			if( !file_exists( $FileName ) )
			{
				self::$CacheExpired = true;
				return false;
			}
			
			if( filemtime( $FileName ) < time() )
			{
				unlink( $FileName );
				self::$CacheExpired = true;
				return null;
			}
			else
			{
				self::$CacheExpired = false;
				return unserialize( file_get_contents( $FileName ) );
			}
		}

		/**
		 * Сохранить данные в кеш
		 * @param mixed $Key - ключ для вычисления MD5 хеша
		 * @param mixed $Prefix - префикс для формирования файла
		 * @param timestamp $Expire - актуальность кеша в секундах
		 * @return bool
		 */
		public function Set( $Data, $Expire = null, $Key = null, $Prefix = null )
		{
			if( self::$Disabled ) return false;
			
			if( is_null( $Key ) ) $Key = self::$Key;
			
			$Key = serialize( $Key );

			if( is_null( $Prefix ) ) $Prefix = self::$Prefix;
			if( is_null( $Expire ) ) $Expire = time() + self::$DefaultExpire;
			else $Expire += time();
			
			$FileName = self::GetFileName( $Key, $Prefix );
			if( !file_exists( $FileName ) )
			{
				self::CreatePreDirs( $FileName );
			}
			file_put_contents( $FileName, serialize( $Data ) );
			touch( $FileName, $Expire );
			return true;
		}


		/**
		 * Возвращает имя файла в зависимости от ключа и префикса
		 * @param mixed $Key - ключ для вычисления MD5 хеша
		 * @param mixed $Prefix - префикс для формирования файла
		 * @return string - сформированное имя файла
		 */
		private function GetFileName( $Key, $Prefix = '' )
		{
			$FileName = md5( $Key );
			if( $Prefix ) $Prefix .= '/';
			return self::$CacheDir . '/' . strtolower( $Prefix ) . self::GetPreDirs( $FileName );
		}

		/**
		 * Возвращает имя файла с директориями по первым буквам
		 * @example если имя файла something.txt => вернется so/me/something.txt
		 * @param string $FileName
		 * @param int $DirCount - вложенность директорий
		 * @param int $SymbolCount - сколько букв имени файла брать для одной директории
		 * @return string
		 */
		private function GetPreDirs( $FileName, $DirCount = 2, $SymbolCount = 2 )
		{
			$Result = '';
			for( $i=0; $i<$DirCount; $i++ ) $Result .= substr( $FileName, $i * $SymbolCount, $SymbolCount ) . '/';
			$Result .= $FileName;
			return $Result;
		}

		/**
		 * Создание директорий на диске для записи файла
		 * @param string $FileName
		 * @return bool
		 */
		public function CreatePreDirs( $FileName )
		{
			$dir = dirname( $FileName );
			if( strtoupper( substr( PHP_OS, 0, 3 ) ) === 'WIN' )
			{
				exec( 'mkdir "' . $dir . '"' );
			} else {
				exec( 'mkdir -p "' . $dir . '"' );
			}
			return true;
		}
	}
?>