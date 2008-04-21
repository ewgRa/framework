<?php
	/**
	 * Класс-контроллер, реализует DataCollector для RedirectView
	 */
	class EngineRedirectDataCollector extends RedirectDataCollector
	{
		function __construct()
		{
			parent::__construct();
			EventDispatcher::RegisterCatcher( 'RequestData', array( $this, 'OnRequestData' ) );
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
			$this->Set( $Data );
		}
	}
?>