<?php
	// FIXME: tested
	// FIXME: refactoring
	class User extends Singleton
	{
		const WRONG_PASSWORD	= 1;
		const WRONG_LOGIN		= 2;
		const SUCCESS_LOGIN		= 3;
		
		const CACHE_LIFE_TIME = 86400;
		
		private $userId = null;
		private $rights = array();

		private static $instance = null;
		
		/**
		 * @return User
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__, self::$instance);
		}
		
		public function getUserId()
		{
			return $this->userId;
		}
		
		public function setUserId($userId)
		{
			$this->userId = $userId;
			return $this;
		}
		
		public function getRights()
		{
			return $this->rights;
		}
		
		public function setRights($rights)
		{
			$this->rights = $rights;
			return $this;
		}
		
		public function loadRights()
		{
			$this->rights = Cache::me()->get(
				array(__CLASS__, __FUNCTION__, $this->userId),
				'site/users'
			);
			
			if(Cache::me()->isExpired())
			{
				$this->rights = array();
				
				if($this->userId)
				{
					$dbQuery = "SELECT t1.* FROM " . Database::me()->getTable('Rights')
						. " t1 INNER JOIN " . Database::me()->getTable('UsersRights_ref')
						. " t2 ON ( t1.id = t2.right_id AND t2.user_id = ? )";

					$dbResult = Database::me()->query(
						$dbQuery, array($this->userId)
					);
					
					while(Database::me()->recordCount($dbResult))
					{
						$inheritanceId = array();

						while($dbRow = Database::me()->fetchArray($dbResult))
						{
							if(in_array($dbRow['alias'], $this->rights))
								continue;

							$inheritanceId[] = $dbRow['id'];
							$this->rights[$dbRow['id']] = $dbRow['alias'];
						}

						$dbQuery = "SELECT t1.* FROM " . Database::me()->getTable('Rights')
							. " t1 INNER JOIN " . Database::me()->getTable('Rights_inheritance')
							. " t2 ON ( t1.id = t2.child_right_id AND t2.right_id IN( ? ) )";

						$dbResult = Database::me()->query(
							$dbQuery,
							array($inheritanceId)
						);
					}
				}
				
				Cache::me()->set($this->rights, self::CACHE_LIFE_TIME);
			}
			
			return $this;
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