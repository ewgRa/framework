<?php
	// FIXME: tested
	// FIXME: refactoring
	class UserEvents extends User 
	{
		public function __construct()
		{
//			$this->Login( '', '' );
			Registry::Set( 'User', $this );

			EventDispatcher::RegisterCatcher( 'DataRequested', array( $this, 'DataRequested' ) );
			EventDispatcher::RegisterCatcher( 'RelativeSessionStarted', array( $this, 'GetLogin' ) );
			EventDispatcher::RegisterCatcher( 'PageDefined', array( $this, 'OnPageDefined' ) );
		}		
		
		public function DataRequested()
		{
			EventDispatcher::ThrowEvent( 'DataProvide', $this->DataProvider() );
		}

		/**
		 * Подписываемся на сбор данных как источник
		 * @return array
		 */
		public function DataProvider()
		{
			return array( 'Data' => array( 'login' => $this->Login, 'rights' => $this->Rights ), 'Prefix' => array( 'USER' ) );
		}

		/**
		 * Функция подписана на событие относительного старта сессии, для определения прав пользователя
		 */
		function OnPageDefined()
		{
			$this->LoadRights();
			EventDispatcher::ThrowEvent( 'UserRightsLoaded', $this->GetRights() );
		}
	}
?>