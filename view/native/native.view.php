<?php
	/**
	 * Класс, реализующий View на основе Native PHP
Каждый шаблон будет сообщать, какой модуль, какой Mode он юзает
$Use = array( 'Module1' => 'Mode1', 'Modules2' => 'Mode2'... );
Подключаем все файлы, которые прикреплены к странице, считывая в буфер их echo, записываем в Section-Position
Передаем полученный результат в layout.
	 */
	class NativeView extends BaseView
	{
		var $PageData = array( 'title' => array(), 'description' => array(), 'keywords' => array() );

		/**
		 * Файл, который будет использован для XSLT преобразования
		 * @var string
		 */
		var $LayoutFile = null;

		/**
		 * Функция обработки и вывода результата
		 * @param array
		 */
		function Process( $Data )
		{
			foreach( $this->PageData as $k => $v )
			{
				foreach( $v as $k2 => $v2 )
				{
					$Data['PAGE'][$k] = str_replace( '%' . $k2 . '%', $v2, $Data['PAGE'][$k] );
				}
			}

			# подключаемые файлы выясняем
			$IncludeFiles = $this->DefineIncludeFiles();
			$PHPFilesMeta = array();
			foreach ( $IncludeFiles['php'] as $file )
			{
				include_once( $file . '.meta.php' );
				foreach( $Meta['modules_match'] as $ModuleName => $Modes )
				{
					foreach( $Modes as $Mode )
					{
						$PHPFilesMeta[$ModuleName][$Mode][] = $file;
					}
				}
			}
			
			# Обрабатываем подключенные модули
			$ViewParts = array();
			foreach( $Data as $ModuleName => $ModuleData )
			{
				foreach ( $ModuleData as $Mode => $MData )
				{
					if( !is_array( $MData ) || !array_key_exists( 'section', $MData ) ) continue;
					
					foreach( $PHPFilesMeta[$ModuleName][$Mode] as $file )
					{
						ob_start();
						include( $file );
						$ViewParts[$MData['section']][$MData['position']] = ob_get_clean();
					}
				}
			}

			$this->OutputHeaders();

			ob_start();
			include_once( $this->LayoutFile );
			$this->Result = ob_get_clean();
		}

		/**
		 * Извлекаем из Settings параметры, которые касаются включения определенных файлов в вывод ( css, javascript, xsl:import файлы и т.д. )
		 * @return array
		 */
		function DefineIncludeFiles()
		{
			# Проходимся по всему массиву настроек и извлекаем файлы, которые были указаны как включаемые куда-либо в представление
			$files = array();
			if( array_key_exists( 'include_files', $this->Settings ) )
			{
				$files = array_unique( $this->Settings['include_files'] );
			}

			# Отделяем "мух от котлет", CSS файлы в один массив, PHP в другой и т.д.
			$IncludeFiles = array( 'php' => array(), 'css' => array(), 'js' => array() );
			foreach ( $files as $file )
			{
				preg_match( '/\.([^\.]+)$/', $file, $matches );
				if( array_key_exists( 1, $matches ) ) $IncludeFiles[$matches[1]][] = $file;
			}
			
			return $IncludeFiles;
		}

		/**
		 * Добавляем в PAGE информацию для шаблона, какие файлы css и javascript надо подключить браузеру
		 * на этом этапе идет компиляция статических файлов-спаек
		 * @param DOMNode $PageNode
		 * @param array $IncludeFiles
		 */
		function AppendPageFiles( $PageNode, $IncludeFiles )
		{
			if( $PageNode->length )
			{
				$CSS = $this->XMLDocument->createElement( 'CSS_FILES' );

				$JoinFilesUrl = Config::GetOption( 'Joined Files' );
				
				if( count( $IncludeFiles['css'] ) && array_key_exists( 'CSS', $JoinFilesUrl ) && $JoinFilesUrl['CSS'] )	$IncludeFiles['css'] = array( $this->GetStaticName( $IncludeFiles['css'], 'css' ) );

				foreach ( $IncludeFiles['css'] as $file )
				{
					$Item = $this->XMLDocument->createElement( 'ITEM' );
					$Item->nodeValue = $file;
					$CSS->appendChild( $Item );
				}

				$PageNode->item(0)->appendChild( $CSS );

				# Добавляем js файлы в XML документ
				$JS = $this->XMLDocument->createElement( 'JAVASCRIPT_FILES' );
				if( count( $IncludeFiles['js'] ) && array_key_exists( 'Javascript', $JoinFilesUrl ) && $JoinFilesUrl['Javascript'] )	$IncludeFiles['js'] = array( $this->GetStaticName( $IncludeFiles['js'], 'js' ) );
				foreach ( $IncludeFiles['js'] as $file )
				{
					$Item = $this->XMLDocument->createElement( 'ITEM' );
					$Item->nodeValue = $file;
					$JS->appendChild( $Item );
				}
				$PageNode->item(0)->appendChild( $JS );
			}
		}


		function GetStaticName( $Files, $Extension )
		{
//			sort( $Files );
			$StaticFileName = md5( join( '#', $Files ) ) . '.' . $Extension;
			if( !file_exists( JOINED_FILES_DIR . DIRECTORY_SEPARATOR . $Extension ) )
			{
				mkdir( JOINED_FILES_DIR . DIRECTORY_SEPARATOR . $Extension, 0755, true );
			}
			file_put_contents( JOINED_FILES_DIR . DIRECTORY_SEPARATOR . $Extension . DIRECTORY_SEPARATOR . $StaticFileName . '.fl', serialize( $Files ) );
			$JoinFilesUrl = Config::GetOption( 'Joined Files' );
			return $JoinFilesUrl['URL']  . '/' . $Extension . '/' . $StaticFileName;
		}


		function Shutdown()
		{
			echo $this->Result;
		}
	}
?>
