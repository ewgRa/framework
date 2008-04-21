<?php
	/**
	 * Базовый контроллер модуля, используемом на странице редиректа. При создании нового модуля от него достаточно унаследоваться.
	 * Автоматически подписывает функцию RedirectProvider на OnData событие, если такая функция имеется
	 */
	abstract class EngineModuleRedirectController
	{
		var $Settings = array();
		var $ControlParams = array();

		function ToEngine( $Settings, $Section, $Position, $PageControllerSettings )
		{
			if( !array_key_exists( 'mode', $Settings ) ) $Settings['mode'] = 'default';
			$this->Settings = $Settings;
			$this->ControlParams = $PageControllerSettings;
			$this->Initialize();
		}

		
		/**
		 * Инициализация модуля
		 */
		function Initialize()
		{
			if( method_exists( $this, 'RedirectProvider' ) )
			{
				EventDispatcher::RegisterCatcher( 'DataRequested', array( $this, 'DataRequested' ) );
			}
		}
		
		function DataRequested()
		{
			EventDispatcher::ThrowEvent( 'DataProvide', $this->RedirectProvider() );
		}
	}
?>