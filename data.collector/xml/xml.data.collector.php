<?php
	/**
	 * Класс реализующий DataCollector на основе XMLDocument'а
	 */
	class XMLDataCollector
	{
		/**
		 * XMLDocument в который записываются поступающие данные
		 * @var DOMDocument
		 */
		var $XMLDocument = null;

		var $Data = array();
		
		/**
		 * Мета данные для переопределния имен узлов
		 * @example @see modules/cart/controllers/cart.controller.php
		 * @var array
		 */
		var $Meta = array();	
		
		
		function __construct()
		{
		}
		
		/**
		 * Функция возвращает данные, которые хранит DataCollector
		 * @return DomDocument
		 */
		function GetData()
		{
//			return $this->GetDataAndCache();
			$ProjectConfig = Config::getOption( 'Project' );
			$this->XMLDocument = new DOMDocument( '1.0', $ProjectConfig['Charset'] );
			$root = $this->XMLDocument->createElement( 'DOCUMENT' );
			$this->XMLDocument->appendChild( $root );
			
			foreach( $this->Data as &$Dta )
			{
				$Node = $this->ASet( $Dta[0], $Dta[1] );
				$root->appendChild( $Node );
				unset( $Dta );
			}
			

			# Обрабатываем Meta-данные
			if( count( $this->Meta ) )
			{
				$xpath = new DOMXPath( $this->XMLDocument );
				foreach( $this->Meta as $k => $Meta )
				{
					$entries = $xpath->query( $k );
					foreach( $entries as $entry )
					{					
						$newNode = $this->XMLDocument->createElement( strtoupper( $Meta[0] ) );
						while( $entry->childNodes->length )
						{
							$newNode->appendChild( $entry->childNodes->item(0) );
						}
						if( array_key_exists( 1, $Meta ) ) $newNode->setAttribute( $Meta[1], $entry->getAttribute( 'key' ) );
						else $newNode->setAttribute( 'key', $entry->getAttribute( 'key' ) );
						$entry->parentNode->replaceChild( $newNode, $entry );
					}
				}
			}

			return $this->XMLDocument;
		}
			
		/**
		 * Функция возвращает данные, которые хранит DataCollector
		 * @return DomDocument
		 */
		function GetDataAndCache()
		{
			$ProjectConfig = Config::getOption( 'Project' );
			$this->XMLDocument = new DOMDocument( '1.0', $ProjectConfig['Charset'] );
			$root = $this->XMLDocument->createElement( 'DOCUMENT' );
			$this->XMLDocument->appendChild( $root );
			
			foreach( $this->Data as &$Dta )
			{
				$XML = Cache::Get( $Dta, 'datacollector/xml' );
				if( Cache::Expired() )
				{
					$Node = $this->ASet( $Dta[0], $Dta[1] );
				}
				else 
				{
					$ProjectConfig = Config::getOption( 'Project' );
					$doc = new DOMDocument( '1.0', $ProjectConfig['Charset']  );
					$doc->loadXML( $XML );
					$Node = $this->XMLDocument->importNode( $doc->documentElement, true );
				}
				$root->appendChild( $Node );
				
				if( Cache::Expired() )
				{
					$XML = $this->XMLDocument->saveXML( $Node );
					Cache::Set( $XML );
				}
	
				unset( $Dta );
			}

			# Обрабатываем Meta-данные
			if( count( $this->Meta ) )
			{
				$xpath = new DOMXPath( $this->XMLDocument );
				foreach( $this->Meta as $k => $Meta )
				{
					$entries = $xpath->query( $k );
					foreach( $entries as $entry )
					{					
						$newNode = $this->XMLDocument->createElement( strtoupper( $Meta[0] ) );
						while( $entry->childNodes->length )
						{
							$newNode->appendChild( $entry->childNodes->item(0) );
						}
						if( array_key_exists( 1, $Meta ) ) $newNode->setAttribute( $Meta[1], $entry->getAttribute( 'key' ) );
						else $newNode->setAttribute( 'key', $entry->getAttribute( 'key' ) );
						$entry->parentNode->replaceChild( $newNode, $entry );
					}
				}
			}
			
			return $this->XMLDocument;
		}

		/**
		 * Функция записи данных в DataCollector
		 * @param array $Data - данные, которые необходимо поместить в DataCollector
		 * @param string $Prefix - имя узла, в который будут помещены данные
		 * @param array $Meta - мета данные для переопределения имен узлов
		 * @param DOMElement $Node - узел, в который будут добавлены данные как дочерний узел
		 * @return DOMElement - сформированный узел
		 */
		function Set( $Data, $Prefix = array( 'ITEM' ), $Meta = array() )
		{
			$this->Data[] = array( $Data, $Prefix );
			
			if( count( $Meta ) )
			{
				$newMeta = array();
				foreach ( $Meta as $k => $M )
				{
					$newMeta['/DOCUMENT/' . strtoupper( $Prefix[0] . '/' . $k )] = $M; 
				}
				$this->Meta = array_merge( $this->Meta, $newMeta );
			}
			return true;
		}
		
		/**
		 * Функция создает узел с именем Prefix и добавляет в него данные
		 * @param array $Data
		 * @param mixed $Prefix
		 * @return DOMElement
		 */
		function ASet( $Data, $Prefix )
		{
			$NodePrefix = array_shift( $Prefix );
			if( is_numeric( $NodePrefix ) )
			{
				$Prefix['key'] = $NodePrefix;
				$NodePrefix = 'ITEM';
			}
			
			if( empty( $NodePrefix ) ) $NodePrefix = 'ITEM';

			$Node = $this->XMLDocument->createElement( strtoupper( $NodePrefix ) );
			foreach( $Prefix as $k => $v )
			{
				$Node->setAttribute( $k, $v );
			}

			if( !is_array( $Data ) )
			{
				$CDATA = $this->XMLDocument->createCDATASection( $Data );
				$Node->appendChild( $CDATA );
			}
			else 
			{
				foreach ( $Data as $k => $v ) $Node->appendChild( $this->ASet( $v, array( $k ) ) );
			}

			return $Node;
		}	

		function ASetString( $Data, $Prefix )
		{
			$NodePrefix = array_shift( $Prefix );
			if( is_numeric( $NodePrefix ) )
			{
				$Prefix['key'] = $NodePrefix;
				$NodePrefix = 'ITEM';
			}
			
			$Node = "<" . strtoupper( $NodePrefix );
			foreach( $Prefix as $k => $v )
			{
				$Node .= ' ' . $k . '="' . $v . '"';
			}
			$Node .= ">";

			if( !is_array( $Data ) )
			{
				$Node .= $Data;
			}
			else 
			{
				foreach ( $Data as $k => $v ) $Node .= ( $this->ASetString( $v, array( $k ) ) );
			}

			$Node .= "</" . strtoupper( $NodePrefix ) . ">";
			return $Node;
		}	
	
	}
?>
