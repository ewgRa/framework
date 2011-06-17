<?php
	/**
	 * Класс-контроллер, реализует DataCollector на основе XMLDocument'а
	 */
	class EngineXMLDataCollector extends XMLDataCollector
	{
		function __construct()
		{
			parent::__construct();
			EventDispatcher::RegisterCatcher( 'RequestData', array( $this, 'OnRequestData' ) );
			EventDispatcher::RegisterCatcher( 'DebugDataRequested', array( $this, 'DebugDataRequested' ) );
		}

		function DebugDataRequested()
		{
			EventDispatcher::ThrowEvent( 'DebugDataProvide', $this->DebugProvider() );
		}
		
		function OnRequestData()
		{
			EventDispatcher::RegisterCatcher( 'DataProvide', array( $this, 'DataReceiver' ) );
			EventDispatcher::ThrowEvent( 'DataRequested' );
		}
		
		/**
		 * Функция подписана как приемник данных, которые необходимо поместить в DataCollector
		 * @param array $Data
		 */
		function DataReceiver( $Data )
		{
			if( !array_key_exists( 'Meta', $Data ) ) $Data['Meta'] = array();
			$this->Set( $Data['Data'], $Data['Prefix'], $Data['Meta'] );
		}
		

		function DebugProvider()
		{
			return array( 'Data' => $this->GetData()->saveXML(), 'Prefix' => 'xml' );
		}		
	}
?>