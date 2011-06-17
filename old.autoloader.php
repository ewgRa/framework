<?php
	/**
	 * Класс, реализующий автозагрузку классов
	 * Данный класс должен быть как можно более самостоятельным, в идеале полностью самостоятельным, потому что он загружается первым
	 * @todo Обходить директори пока не найдем нужный класс. В cache скидывать только найденные классы
	 */
	class OldClassAutoLoader
	{
		/**
		 * Массив классов, найденых в $ClassDirectory
		 * @var string
		 */
		var $ClassList = array();
	
		/**
		 * Класс кеширования данных, используемых для подключения
		 * @var CacheClassList
		 */
		var $Cache;
	
		/**
		 * Массив директорий, в которых производится поиск классов
		 * @var array_of_string
		 */
		var $ClassDirectory;
	
		/**
		 * Флаг, указывающий что данные взяли из кеша
		 * @var bool
		 */
		var $CacheFlag = false;
		
		/** for Singlton pattern */
		static $instance = false;
		function getInstance()
		{
			if( !self::$instance )
			{
				$Reflection = new ReflectionClass( __CLASS__  );
				$fargs = func_get_args();
				self::$instance = call_user_func_array( array( &$Reflection, 'newInstance' ), $fargs );
			}
			return self::$instance;
		}
	
		/**
		 * Конструктор
		 * @param array $ClassDirectory массив директорий в которых производится поиск классов
		 * @param string $CacheFile файл для кеширования
		 * @return ClassAutoLoader
		 */
		function __construct( $ClassDirectory = array(), $CacheFile = 'autoloader_cache.txt' )
		{
			$this->Cache = new CacheClassAutoLoader( $CacheFile );
			$this->ClassDirectory = $ClassDirectory;
			$this->GetClassList();
		}
	
		/**
		 * Непосредственная загрузка файла, где расположен класс $ClassName
		 *
		 * @param string $ClassName имя загружаемого класса
		 * @return string имя файла, где расположен класс
		 */
		function Load( $ClassName )
		{
			if( class_exists( $ClassName ) ) return true;
			
			$ClassName = strtolower( $ClassName );
	
			$cache_expire = false;
			$result = false;
			# Проверяем, устарели ли закешированые данные
			if( $this->CacheFlag && ( !isset( $this->ClassList[$ClassName] ) || !file_exists( $this->ClassList[$ClassName] ) ) )
			{	# данные устарели: или класс не был обнаружен в списке, или файл отсутствует
				# очищаем кеш, перегружаем данные
				$cache_expire = true;
			}
			elseif( isset( $this->ClassList[$ClassName] ) )
			{
				include_once( $this->ClassList[$ClassName] );
				#проверяем, появился ли у нас класс, возможно данные взяты из кеша, но класс переместили в другой файл
				if( $this->CacheFlag && !class_exists( $ClassName ) ) $cache_expire = true;
				else $result = $this->ClassList[$ClassName];
			}
			if( $cache_expire )
			{
				$this->Cache->Clear();
				$this->GetClassList();
				$result = $this->Load( $ClassName );
			}
			return $result;
		}
	
		/**
		 * Загрузка списка файлов, которые находятся в директориях $directory
		 * @param array $directory массив директорий которые надо обойти
		 * @return array массив файлов
		 */
		function GetFileList( $directory = array() )
		{
			$files = array();
			foreach ( $directory as $dir )
			{
				$filelist = glob( is_array( $dir ) ? $dir[0] . "/*" : $dir . "/*" );
				if( $filelist )
				{
					foreach( $filelist as $file )
					{
						$file = str_replace( '\\', '/', $file );
						if( is_dir( $file ) && !is_array( $dir ) )
						{
							$files = array_merge( $files, $this->GetFileList( array( $file ) ) );
						}
						elseif( !is_dir( $file ) )
						{
							$files[] = realpath( $file );
						}
					}
				}
			}
			return $files;
		}
			
		/**
		 * Загрузка списка классов и в каком файле каждый класс объявлен
		 */
		function GetClassList()
		{
			$cache_data = $this->Cache->Get();
			if( $cache_data )
			{	//get ClassList array from cache
				$this->ClassList = unserialize( $cache_data );
				$this->CacheFlag = true;
			}
			else
			{
				$this->CacheFlag = false;
				$this->ClassList = array();
				$FileList = $this->GetFileList( $this->ClassDirectory );
				$i=0;
				foreach( $FileList as $File )
				{
					$i++;
					if( is_file( $File ) )
					{
						$FileClasses = array();
						$file_data =  file_get_contents( $File );
						//file_put_contents( 'cache/' . time() . microtime( true), $file_data );
						$token = @token_get_all( $file_data );
						$is_class = false;
						foreach ( $token as $t )
						{
							if( $t[0] == T_CLASS )
							{
								$is_class = true;
							}
							elseif ( $t[0] == T_STRING && $is_class )
							{
								$FileClasses[] = strtolower( $t[1] );
								$is_class = false;
							}
						}
						foreach( $FileClasses as $Class )
						{
							if( isset( $this->ClassList[$Class] ) )
							{
								die( "Autoload conflict: class '{$Class}' from file `{$File}` already load from file `{$this->ClassList[$Class]}`" );
							}
							$this->ClassList[$Class] = $File;
						}
						unset( $token );
						unset( $file_data );
					}
				}
				$this->Cache->Set( serialize( $this->ClassList ) );
			}
		}
	}
	
	/**
	 * Класс кеширования для данных для ClassAutoLoader
	 */
	class CacheClassAutoLoader
	{
		/**
		 * Файл, в котором будут храниться кешируемые данные
		 * @var string
		 */
		var $CacheFile;
	
		/**
		 * Конструктор
		 * @param string $CacheFile файл, где будет храниться кеш
		 */
		function __construct( $CacheFile )
		{
			$this->CacheFile = $CacheFile;
		}
	
		/**
		 * Очистка кеша
		 */
		function Clear()
		{
			unlink( $this->CacheFile );
		}
	
		/**
		 * Кеширование данных
		 */
		function Set( $Data )
		{
			file_put_contents( $this->CacheFile, $Data );
		}
	
		/**
		 * Загрузка кеша
		 */
		function Get()
		{
			if( file_exists( $this->CacheFile ) )
			{
				return file_get_contents( $this->CacheFile );
			}
			return false;
		}
	}
	
	function __autoload( $class_name )
	{
		ClassAutoLoader::getInstance()->Load( $class_name );
	}
?>