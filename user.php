<?php
	/**
	 * Класс-контроллер, реализовывающий Пользователя
	 */
	class EngineUser extends User
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
	

	/**
	 * Класс, реализующий посетителя сайта
	 */
	class User
	{
		# Константы для результата авторизации
		const WRONG_PASSWORD = 1; # пароль неверен
		const WRONG_LOGIN = 2; # логин отсутствует
		const SUCCESS_LOGIN = true; # авторизация прошла успешно
		
		/**
		 * Логин пользователя в системе
		 * @var string
		 */
		var $Login = null;

		/**
		 * Массив прав, которые пользователь имеет на сайте
		 * @var array
		 */
		var $Rights = array();
		
		/**
		 * Получить права пользователя
		 * @return array
		 */
		function GetRights()
		{
			return $this->Rights;
		}
		
		/**
		 * Функция загрузки прав пользователя
		 * @return array
		 */
		function LoadRights()
		{
			$this->Rights = Cache::Get( array( 'Load Rights', $this->Login ), 'engine/user' );
			if( Cache::Expired() )
			{
				$this->Rights = array();
				if( $this->Login )
				{
					$DB = Registry::Get( 'DB' );
					$dbq = "SELECT t1.* FROM " . $DB->TABLES['Rights'] . " t1 INNER JOIN " . $DB->TABLES['UsersRights_ref'] . " t2 ON ( t1.id = t2.right_id AND t2.user_id = ? )";
					$dbr = $DB->Query( $dbq, array( $this->Login ) );
					
					while( $DB->RecordCount( $dbr ) )
					{
						$CurrentIDs = array();
						while( $db_row = $DB->FetchArray( $dbr ) )
						{
							if( in_array( $db_row['alias'], $this->Rights ) ) continue;
							$CurrentIDs[] = $db_row['id'];
							$this->Rights[$db_row['id']] = $db_row['alias'];
						}

						$dbq = "SELECT t1.* FROM " . $DB->TABLES['Rights'] . " t1 INNER JOIN " . $DB->TABLES['Rights_inheritance'] . " t2 ON ( t1.id = t2.child_right_id AND t2.right_id IN( ? ) )";
						$dbr = $DB->Query( $dbq, array( $CurrentIDs ) );
					}
				}
				Cache::Set( $this->Rights, 24*60*60 );
			}
			
			return $this->Rights;
		}
		
		/**
		 * Функция получения логина, если логин еще не был определен - он извлекается из сесии
		 * @return string
		 */
		function GetLogin()
		{
			if( is_null( $this->Login ) )
			{
				$Session = Registry::Get( 'Session' );
				if( $Session->isStart() && array_key_exists( 'login', $Session->Data ) )
				{
					$this->Login = $Session->Data['login'];
				}
			}
			return $this->Login;
		}
		
		/**
		 * Авторизация пользователя
		 * @param string $Login - логин пользователя
		 * @param string $Password - пароль пользователя
		 * @return int - статус авторизации
		 */
		function Login( $Login, $Password )
		{
			$DB = Registry::Get( 'DB' );
			$dbq = "SELECT *, password = MD5( ? ) as verify_password FROM " . $DB->TABLES['Users'] . " WHERE login = ?";
			$dbr = $DB->Query( $dbq, array( $Password, $Login ) );
			if( $DB->RecordCount( $dbr ) )
			{
				$db_row = $DB->FetchArray( $dbr );
				if( $db_row['verify_password'] )
				{
					$Session = Registry::Get( 'Session' );
					$Session->Start();
					$Session->Data['login'] = $db_row['id'];
					$Session->Save();
					return self::SUCCESS_LOGIN;
				}
				else return self::WRONG_PASSWORD;
			}
			else return self::WRONG_LOGIN;
		}
	}
?>