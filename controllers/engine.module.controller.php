<?php
	/**
	 * Базовый контроллер модуля. При создании нового модуля от него достаточно унаследоваться.
	 * Автоматически подписывает функцию DataProvider на OnData событие, если такая функция имеется
	 * Автоматически выполняет функцию SetCacheParams если таковая имеется
	 */
	abstract class EngineModuleController
	{
		/**
		 * Мета-данные. Например для преобразования XML узлов
		 * @var array
		 */
		var $Meta = array();
		
		/**
		 * Установки из десереализованного массива PagesModules_ref.module_settings, секция и позиция куда модуль предназначено вставить
		 */
		var $Settings = array(), $Section = null, $Position = null;
		
		/**
		 * Параметры для контроллеров из PagesParams
		 * @var array
		 */
		var $ControlParams = array();
		
		/**
		 * Массив для хранения подписывающихся функций
		 * @var array
		 */
		var $Subscribers = array();
		
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
			$this->Section = $Section;
			$this->Position = $Position;
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
			$Result = $this->CallFunctionUseCache( array( $this, 'DataProvider' ) );
			$Return = array();
			$Return['Data'] = array( 'Data' => $Result, 'Params' => $this->ControlParams, 'Settings' => $this->Settings );
			$Return['Prefix'] = array( str_ireplace( 'controller', '', get_class( $this ) ) );
			$Return['Prefix']['mode'] = $this->Settings['mode'];
			$Return['Prefix']['section'] = $this->Section;
			$Return['Prefix']['position'] = $this->Position;
			$Return['Meta'] = array_key_exists( 'DataProvide', $this->Meta ) ? $this->Meta['DataProvide'] : array();
			
			EventDispatcher::ThrowEvent( 'DataProvide', $Return );
		}
		
		
		
		function ThrowEvent( $EventName, $Function )
		{
			EventDispatcher::ThrowEvent( $EventName, $this->CallFunctionUseCache( $Function ) );
		}


		/**
		 * Вызов функции в зависимости от кеш-данных. 
		 * Если кеш-данные отсутствуют, то вызывается функция и возвращаемые данные кладуться в кеш.
		 * Если кеш-данные есть, то возвращаются они
		 * @param function $Function
		 * @return mixed
		 */
		function CallFunctionUseCache( $Function, $Arguments = array() )
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
					$Result = call_user_func_array( $Function, $Arguments );
					Cache::Set( $Result, $CacheLifeTime );
				}
			}
			else $Result = call_user_func_array( $Function, $Arguments );
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