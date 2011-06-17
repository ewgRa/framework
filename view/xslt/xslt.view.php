<?php
	/**
	 * Класс, реализующий View на основе XML/XSLT
	 */
	class XSLTView extends BaseView
	{
		var $PageData = array( 'title' => array(), 'description' => array(), 'keywords' => array() );
		var $Page = array();
		
		/**
		 * Файл, который будет использован для XSLT преобразования
		 * @var string
		 */
		var $LayoutFile = null;

		/**
		 * XSLT шаблон, загруженный в DOMDocument
		 * @var DOMDocument
		 */
		var $XSLDocument = null;

		/**
		 * XML документ, для которого выполняется XSLT преобразование
		 * @var DOMDocument
		 */
		var $XMLDocument = null;

		/**
		 * Функция обработки и вывода результата XSLT преобразования
		 * @param DOMDOcument $XMLDocument
		 */
		function Process( $XMLDocument )
		{
			$this->XMLDocument = $XMLDocument;

			$xpath = new DOMXPath( $this->XMLDocument );

			foreach( $this->Page as $k => $v )
			{
				$entries = $xpath->query( '/DOCUMENT/PAGE/' . strtoupper( $k ) );
				$Node = $entries->item(0);
				$a = $v;
				$Node->replaceChild( $XMLDocument->createTextNode( $a ), $Node->childNodes->item( 0 ) );
			}
			
			foreach( $this->PageData as $k => $v )
			{
				$entries = $xpath->query( '/DOCUMENT/PAGE/' . strtoupper( $k ) );
				$Node = $entries->item(0);
				foreach( $v as $k2 => $v2 )
				{
					if(!is_array($k2) && !is_array($v2) && !is_array($Node->nodeValue))
					{
						$a = str_replace( '%' . $k2 . '%', $v2, $Node->nodeValue );
						$Node->replaceChild( $XMLDocument->createTextNode( $a ), $Node->childNodes->item( 0 ) );
					}
				}
			}

			# подключаемые файлы выясняем
			$IncludeFiles = $this->DefineIncludeFiles();
			$this->ProcessXSLT( $IncludeFiles['xsl'] );

			# Добавляем css файлы в XML документ
			$PageNode = $this->XMLDocument->documentElement->getElementsByTagName( 'PAGE' );
			$this->AppendPageFiles( $PageNode, $IncludeFiles );


			$this->OutputHeaders();

			$this->Transform();
		}

		function LoadLayout()
		{
			# Загружаем XSL документ
			if( $this->LayoutFile )
			{
				$current_dir = realpath( '.' );
				
				if (isset($_SERVER['DOCUMENT_ROOT']) && file_exists($_SERVER['DOCUMENT_ROOT']))
					chdir($_SERVER['DOCUMENT_ROOT']);
				
				$this->XSLDocument = DomDocument::loadXML( file_get_contents( $this->LayoutFile ) );
				chdir($current_dir);
			}
			else
			{
				die( 'no layout xslt file!!! ' . $this->LayoutFile );
			}
		}

		function Transform()
		{
			$proc = new XsltProcessor();
			$xsl = $proc->importStylesheet( $this->XSLDocument );
			$this->Result = $proc->transformToXML( $this->XMLDocument );
		}

		/**
		 * Обработка XSLT шаблона, добавление в него необходимых xsl:import елементов и т.д.
		 * @param array $ImportFiles
		 */
		function ProcessXSLT( $ImportFiles )
		{
			$this->LoadLayout();
			$ImportFiles = array_diff( $ImportFiles, array( $this->LayoutFile ) );
			# Добавляем xsl файлы в XSL документ
			$this->AppendXSLTImportFiles( $this->XSLDocument->documentElement, $ImportFiles );
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

			# Отделяем "мух от котлет", CSS файлы в один массив, XSLT в другой и т.д.
			$IncludeFiles = array(
				'xsl' => array(),
				'css' => array(),
				'js' => array(),
				'dont_split' => isset( $this->Settings['dont_split_files'] ) ? $this->Settings['dont_split_files'] : array()
			);
		
			foreach ( $files as $file )
			{
				preg_match( '/\.([^\.]+)$/', $file, $matches );
				if( array_key_exists( 1, $matches ) ) $IncludeFiles[$matches[1]][] = $file;
			}
			return $IncludeFiles;
		}

		/**
		 * Добавляем XSL-файлы как xsl:import директивы в XSLT шаблон
		 * @param DOMNode $DocumentElement
		 * @param array $XSLTIncludeFiles
		 */
		function AppendXSLTImportFiles( $DocumentElement, $XSLTIncludeFiles )
		{
			foreach ( $XSLTIncludeFiles as $file )
			{
				$ImportNode = $this->XSLDocument->createElementNS( $DocumentElement->namespaceURI, 'xsl:import' );
				$ImportNode->setAttribute( 'href', $file );
				$DocumentElement->insertBefore( $ImportNode, $DocumentElement->firstChild->nextSibling );
			}
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
				
				if(!$JoinFilesUrl)
				    $JoinFilesUrl = array();
				
				if(!isset($IncludeFiles['css']))
				    $IncludeFiles['css'] = array();
				
				if(!isset($IncludeFiles['js']))
				    $IncludeFiles['js'] = array();

				if( count( $IncludeFiles['css'] ) && array_key_exists( 'CSS', $JoinFilesUrl ) && $JoinFilesUrl['CSS'] )
				{
					$IncludeFiles['css'] = array_merge(
						array( $this->GetStaticName( array_diff( $IncludeFiles['css'], $IncludeFiles['dont_split'] ), 'css' ) ),
						array_intersect( $IncludeFiles['css'], $IncludeFiles['dont_split'] )
					);
				}

				foreach ( $IncludeFiles['css'] as $file )
				{
					$Item = $this->XMLDocument->createElement( 'ITEM' );
					$Item->nodeValue = $file;
					$CSS->appendChild( $Item );
				}

				$PageNode->item(0)->appendChild( $CSS );

				# Добавляем js файлы в XML документ
				$JS = $this->XMLDocument->createElement( 'JAVASCRIPT_FILES' );
				if( count( $IncludeFiles['js'] ) && array_key_exists( 'Javascript', $JoinFilesUrl ) && $JoinFilesUrl['Javascript'] )
				{
					$IncludeFiles['js'] = array_merge(
						array( $this->GetStaticName( array_diff( $IncludeFiles['js'], $IncludeFiles['dont_split'] ), 'js' ) ),
						array_intersect( $IncludeFiles['js'], $IncludeFiles['dont_split'] )
						);
				}

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
