<?php
	/**
	 * Класс, реализующий автозагрузку классов
	 * Данный класс должен быть как можно более самостоятельным, в идеале полностью самостоятельным, потому что он загружается первым
	 * @todo Обходить директори пока не найдем нужный класс. В cache скидывать только найденные классы
	 */
	class ClassAutoLoader
	{
		/**
		 * Массив классов, найденых в $SearchDirectories
		 * @var string
		 */
		var $ClassList = array();
	
		/**
		 * Массив директорий, в которых производится поиск классов
		 * @var array_of_string
		 */
		var $SearchDirectories;
		
		
		var $CacheFile;
		
		var $FindClassRuntimeCache = array( 'filelist' => array(), 'class_list' => array(), 'already_token_files' => array() );
	
		/** for Singlton pattern */
		static $instance = false;
		public static function getInstance()
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
		 * @param array $SearchDirectories массив директорий в которых производится поиск классов
		 * @param string $CacheFile файл для кеширования
		 * @return ClassAutoLoader
		 */
		function __construct( $SearchDirectories = array(), $CacheFile = 'autoloader_cache.txt' )
		{
			$this->CacheFile = $CacheFile;
			$this->SearchDirectories = $SearchDirectories;
			if( file_exists( $CacheFile ) )
			{
				$this->ClassList = unserialize( file_get_contents( $CacheFile ) );
			}
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
	
			# Проверяем, устарели ли закешированые данные
			if( !array_key_exists( $ClassName, $this->ClassList ) || !file_exists( $this->ClassList[$ClassName] ) )
			{	# данные устарели: или класс не был обнаружен в списке, или файл отсутствует
				# Попробуем найти класс
				$ClassFile = $this->FindClass( $ClassName );
				if( $ClassFile )
				{
					# Обновляем кеш, подключаем класс
					$this->ClassList[$ClassName] = realpath( $ClassFile );
					include_once( $ClassFile );
				}
				elseif( array_key_exists( $ClassName, $this->ClassList ) )
				{
					unset( $this->ClassList[$ClassName] );
				}
				file_put_contents( $this->CacheFile, serialize( $this->ClassList ) );
			}
			else
			{
				include_once( $this->ClassList[$ClassName] );
				#проверяем, появился ли у нас класс, возможно данные взяты из кеша, но класс переместили в другой файл
				if( !class_exists( $ClassName ) )
				{
					$ClassFile = $this->FindClass( $ClassName );
					if( $ClassFile )
					{
						# Обновляем кеш, подключаем класс
						$this->ClassList[$ClassName] = $ClassFile;
						include_once( $ClassFile );
					}
					elseif( array_key_exists( $ClassName, $this->ClassList ) )
					{
						unset( $this->ClassList[$ClassName] );
					}
					file_put_contents( $this->CacheFile, serialize( $this->ClassList ) );
				}
			}
		}
	
		/**
		 * Загрузка списка файлов, которые находятся в директориях $directory
		 * @param array $directory массив директорий которые надо обойти
		 * @return array массив файлов
		 */
		function FindClass( $ClassName, $Directories = null )
		{
			if( array_key_exists( $ClassName, $this->FindClassRuntimeCache['class_list'] ) )
			{
				return $this->FindClassRuntimeCache['class_list'][$ClassName];
			}
			if( is_null( $Directories ) ) $Directories = $this->SearchDirectories;
			foreach ( $Directories as $dir )
			{
				$dirname = is_array( $dir ) ? $dir[0] : $dir;
				$filelist = array();
				if( array_key_exists( $dirname, $this->FindClassRuntimeCache['filelist'] ) )
				{
					$filelist = $this->FindClassRuntimeCache['filelist'][$dirname];
				}
				else 
				{
					$filelist = glob( $dirname . "/*" );
					$this->FindClassRuntimeCache['filelist'][$dirname] = $filelist;
				}
				
				if( $filelist )
				{
					foreach( $filelist as $file )
					{
						if( array_key_exists( $file, $this->FindClassRuntimeCache['already_token_files'] ) ) continue;

						$file = str_replace( '\\', '/', $file );
						if( is_dir( $file ) && !is_array( $dir ) )
						{
							$ClassFile = $this->FindClass( $ClassName, array( $file ) );
							if( $ClassFile )
							{
								return $ClassFile;
							}
						}
						elseif( is_file( $file ) )
						{
							$file_data =  file_get_contents( $file );
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
									$is_class = false;
									if( strtolower( $t[1] ) == $ClassName )	return $file;
									else 
									{
										$this->FindClassRuntimeCache['class_list'][strtolower( $t[1] )] = $file;
									}
								}
							}
						}
						$this->FindClassRuntimeCache['already_token_files'][$file] = true;
					}
				}
			}
			return false;
		}
	}
		
	function __autoload( $class_name )
	{
		ClassAutoLoader::getInstance()->Load( $class_name );
	}
?>