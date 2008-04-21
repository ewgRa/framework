<?php
	/**
	 * Базовый контроллер модуля. При создании нового модуля от него достаточно унаследоваться.
	 * Автоматически подписывает функцию DataProvider на OnData событие, если такая функция имеется
	 * Автоматически выполняет функцию SetCacheParams если таковая имеется
	 */
	abstract class EngineModuleJsonController
	{
		var $Settings = array();
		
		/**
		 * Параметры для контроллеров из PagesParams
		 * @var array
		 */
		var $ControlParams = array();
		
		/**
		 * Параметры для ключа кеша
		 * @var array
		 */
		var $CacheParams = array( /* FunctionName => array( 'prefix' => ..., 'key' => array( ... ), 'life_time' => ... ) */ );

		/**
		 * Прикрепление модуля к движку
		 * @see ModuleDispatcher
		 * @param array $Settings
		 * @param int $Section
		 * @param int $Position
		 * @param array $PageControllerSettings
		 */
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
			if( method_exists( $this, 'SetCacheParams' ) ) $this->SetCacheParams();

			if( method_exists( $this, 'DataProvider' ) )
			{
				EventDispatcher::RegisterCatcher( 'DataRequested', array( $this, 'DataRequested' ) );
			}
		}
		
		
		function DataRequested()
		{
			EventDispatcher::ThrowEvent( 'DataProvide', $this->CallFunctionUseCache( array( $this, 'DataProvider' ) ) );
		}


		/**
		 * Вызов функции в зависимости от кеш-данных. 
		 * Если кеш-данные отсутствуют, то вызывается функция и возвращаемые данные кладуться в кеш.
		 * Если кеш-данные есть, то возвращаются они
		 * @param function $Function
		 * @return mixed
		 */
		function CallFunctionUseCache( $Function )
		{
			$CacheKey = $CachePrefix = $CacheLifeTime = null;
			$FunctionName = $Function[1];
			if( array_key_exists( $FunctionName, $this->CacheParams ) )
			{
				$this->DefineCacheSettings( $this->CacheParams[$FunctionName], $this->Settings['mode'], $CacheKey, $CachePrefix, $CacheLifeTime );
			}

			$Result = null;
			if( $CacheKey && $CachePrefix && $CacheLifeTime )
			{
				$Result = Cache::Get( $CacheKey, $CachePrefix );
				if( Cache::Expired() )
				{
					$Result = call_user_func( $Function );
					Cache::Set( $Result, $CacheLifeTime );
				}
			}
			else $Result = call_user_func( $Function );
			return $Result;
		}
		

		/**
		 * Настройки кеша
		 * @param array $CacheParams
		 * @param string $Mode
		 * @param string $Key
		 * @param string $Prefix
		 * @param int $LifeTime
		 */
		function DefineCacheSettings( $CacheParams, $Mode, &$Key, &$Prefix, &$LifeTime )
		{
			$Key = $Prefix = $LifeTime = null;
			# Ищем настройки в дефолтной зоне
			if( array_key_exists( 0, $CacheParams ) )
			{
				if( array_key_exists( 'key', $CacheParams[0] ) ) $Key = $CacheParams[0]['key'];
				if( array_key_exists( 'prefix', $CacheParams[0] ) ) $Prefix = $CacheParams[0]['prefix'];
				if( array_key_exists( 'life_time', $CacheParams[0] ) ) $LifeTime = $CacheParams[0]['life_time'];
				if( array_key_exists( 'callback_functions', $CacheParams[0] ) ) $CallbackFunctions = $CacheParams[0]['callback_functions'];
			}
			# Устанавливаем настройки в зависимости от $Mode
			if( array_key_exists( $Mode, $CacheParams ) )
			{
				if( array_key_exists( 'key', $CacheParams[$Mode] ) ) $Key = $CacheParams[$Mode]['key'];
				if( array_key_exists( 'prefix', $CacheParams[$Mode] ) ) $Prefix = $CacheParams[$Mode]['prefix'];
				if( array_key_exists( 'life_time', $CacheParams[$Mode] ) ) $LifeTime = $CacheParams[$Mode]['life_time'];
//				if( array_key_exists( 'life_time', $CacheParams[$Mode] ) ) $LifeTime = $CacheParams[$Mode]['life_time'];
			}
		}
	}
?>