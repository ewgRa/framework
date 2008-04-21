<?php
	/**
	 * Класс-контроллер, реализующий сессию
	 */
	class EngineSession extends Session
	{
		public function __construct()
		{
			Registry::Set( 'Session', $this );
			$EngineDispatcher = Registry::Get( 'EngineDispatcher' );

			EventDispatcher::RegisterCatcher( 'EngineStarted', array( $this, 'RelativeStart' ) );
		}

		/**
		 * Подписываемся на событие OnStart чтобы стартовать сессию, если она уже была запущена при предыдущих посещениях
		 */
		public function RelativeStart()
		{
			if( parent::RelativeStart() )
			{
				EventDispatcher::ThrowEvent( 'RelativeSessionStarted' );
			}
		}
	}


	/**
	 * Класс реализующий сессию
	 */
	class Session
	{
		/**
		 * Данные, которые необходимы хранятся в сессии для данного пользователя
		 * @var array
		 */
		var $Data = array();

		/**
		 * Флаг, показывающий стартовала ли сессия
		 * @var boolean
		 */
		var $Started = false;


		/**
		 * Относительный старт сессии, то есть стартовать сессию, если она
		 * уже стартовала для этого посетителя при предыдущих посещениях сайта
		 */
		function RelativeStart()
		{
			if ( isset( $_REQUEST[session_name()] ) )
			{
				$this->Start();
				return true;
			}
			return false;
		}

		/**
		 * Старт сессии
		 */
		function Start()
		{
			if( !$this->Started )
			{
				$this->Started = true;
				session_start();
				$this->Data = $_SESSION;
				return true;
			}
			return false;
		}

		/**
		 * Стартовала ли сессия
		 * функция дублер (оставлена для совместимости)
		 * @return boolean
		 */
		function isStart()
		{
			return $this->isStarted();
		}

		/**
		 * Стартовала ли сессия
		 * @return boolean
		 */
		function isStarted()
		{
			return $this->Started;
		}

		/**
		 * Сохранение сессиии
		 * @return boolean
		 */
		function Save()
		{
			if( isset( $_SESSION ) )
			{
				foreach ( $_SESSION  as $k => $v ) session_unregister( $k );
			}
			foreach ( $this->Data as $k => $v )
			{
				session_register( $k );
				$_SESSION[$k] = $v;
			}
			return true;
		}

		/**
		 * Установка Cookie
		 *
		 * @param string $Name - наименование куки
		 * @param string $Data - значение куки
		 * @param timestamp $Expire - дата истечения куки
		 * @param string $Path - путь
		 * @return boolean
		 */
		function SetCookie( $Name, $Data, $Expire = null, $Path = '/', $Domain = null )
		{
			if( is_null( $Domain ) ) $Domain = $_SERVER['HTTP_HOST'];
			setcookie( $Name, $Data, $Expire, $Path, $Domain );
			return true;
		}
		
		/**
		 * Получить ID сессии
		 */
		function GetID()
		{
			if( $this->isStarted() )
			{
				return session_id();
			}
		}
	}
?>