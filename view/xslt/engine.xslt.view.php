<?php
	/**
	 * Класс-контроллер, реализующий View на основе XML/XSLT
	 */
	class EngineXSLTView extends XSLTView 
	{
		/**
		 * При создании класс регистрируем событие OnView и подписываемся на него как приниматель данных
		 * @return EngineXSLTView
		 */
		function __construct()
		{
			parent::__construct();
			EventDispatcher::RegisterCatcher( 'View', array( $this, 'OnView' ) );
			EventDispatcher::RegisterCatcher( 'DebugDataRequested', array( $this, 'DebugDataRequested' ) );
		}


		function DebugDataRequested()
		{
			EventDispatcher::ThrowEvent( 'DebugDataProvide', $this->DebugProvider() );
		}
		
		/**
		 * Функция, отвечающая за прием настроек отображения от источников
		 * @param array $Settings - настройки отображения
		 */
		function ViewSettingsReceiver( $Settings )
		{
			$this->Settings = array_merge( $this->Settings, $Settings );
		}
		
		function PageHeadersReceiver( $Headers )
		{
			$this->Headers = array_merge( $this->Headers, $Headers );
		}

		/**
		 * Функция отвечающая за прием пути к файлу шаблона
		 * @param string $LayoutFile
		 */
		function GetLayoutFileReceiver( $LayoutFile )
		{
			$this->LayoutFile = $LayoutFile;
		}

		/**
		 * Подписываемся примеником на событие загрузки прав пользователя (OnLoadUserRights) чтобы проверить имеет ли он на право доступа к этой странице
		 * @param array $Rights - права пользователя
		 */
		public function PageDataReceiver( $Data )
		{
			foreach( $Data as $key => $value )
			{
				$this->Page[$key] = $value;
			}
		}
				
		public function PageAdditionalDataReceiver( $Data )
		{
			foreach( $Data as $k => $v )
			{
				if( !array_key_exists( $k, $this->PageData ) ) $this->PageData[$k] = array();
				$this->PageData[$k] = array_merge( $this->PageData[$k], $v );
			}
		}
		
		/**
		 * Функция отвечает за выдачу результата работы сервера пользователю
		 * @param array $Data
		 */
		function OnView( $Data )
		{
			EventDispatcher::RegisterCatcher( 'LayoutFileProvide', array( $this, 'GetLayoutFileReceiver' ) );
			EventDispatcher::ThrowEvent( 'LayoutFileRequested' );

			EventDispatcher::RegisterCatcher( 'ViewSettingsProvide', array( $this, 'ViewSettingsReceiver' ) );
			EventDispatcher::ThrowEvent( 'ViewSettingsRequested' );

			EventDispatcher::RegisterCatcher( 'PageAdditionalDataProvide', array( $this, 'PageAdditionalDataReceiver' ) );
			EventDispatcher::ThrowEvent( 'PageAdditionalDataRequested' );

			EventDispatcher::RegisterCatcher( 'PageDataProvide', array( $this, 'PageDataReceiver' ) );
			EventDispatcher::ThrowEvent( 'PageDataRequested' );
			
			EventDispatcher::RegisterCatcher( 'PageHeadersProvide', array( $this, 'PageHeadersReceiver' ) );
			EventDispatcher::ThrowEvent( 'PageHeadersRequested' );

			$this->Process( $Data );
		}
		
		
		function DebugProvider()
		{
			if( $this->XSLDocument )
			{
				$xml = $this->XSLDocument->saveXML();
			}
			else 
			{
				$xml = '';
			}
			return array( 'Data' => $xml, 'Prefix' => 'xslt' );
		}
	}
?>