<?php
	// FIXME: tested
	// FIXME: refactoring
	class User extends Singleton
	{
		const WRONG_PASSWORD	= 1;
		const WRONG_LOGIN		= 2;
		const SUCCESS_LOGIN		= 3;
		
		const CACHE_LIFE_TIME = 86400;
		
		private $id = null;
		private $login = null;
		private $rights = array();

		private static $instance = null;
		
		/**
		 * @return User
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__, self::$instance);
		}
		
		public function getId()
		{
			return $this->id;
		}
		
		private function setId($id)
		{
			$this->id = $id;
			return $this;
		}
		
		public function getLogin()
		{
			return $this->login;
		}
		
		private function setLogin($login)
		{
			$this->login = $login;
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
		
		private function loadRights()
		{
			$this->rights = Cache::me()->get(
				array(__CLASS__, __FUNCTION__, $this->getId()),
				'site/users'
			);
			
			if(Cache::me()->isExpired())
			{
				$this->rights = array();
				
				if($this->getId())
				{
					$dbQuery = "SELECT t1.* FROM " . Database::me()->getTable('Rights')
						. " t1 INNER JOIN " . Database::me()->getTable('UsersRights_ref')
						. " t2 ON ( t1.id = t2.right_id AND t2.user_id = ? )";

					$dbResult = Database::me()->query(
						$dbQuery, array($this->getId())
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
		
		public function login($login, $password)
		{
			Session::start();
			Session::drop('user');
			Session::save();
			
			$dbQuery = "SELECT *, password = MD5( ? ) as verify_password FROM "
				. Database::me()->getTable('Users') . " WHERE login = ?";

			$dbResult = Database::me()->query($dbQuery, array($password, $login));

			if(Database::me()->recordCount($dbResult))
			{
				$dbRow = Database::me()->fetchArray($dbResult);
				
				if($dbRow['verify_password'])
				{
					$this->setId($dbRow['id']);
					$this->setLogin($dbRow['login']);
					$this->loadRights();
					
					Session::set(
						'user',
						array(
							'id' => $this->getId(),
							'login' => $this->getLogin(),
							'rights' => $this->getRights()
						)
					);
					Session::save();
					return self::SUCCESS_LOGIN;
				}
				else return self::WRONG_PASSWORD;
			}
			else return self::WRONG_LOGIN;
		}
		
		public function sessionStarted()
		{
			$user = Session::me()->get('user');
			if($user)
			{
				$this->setId($user['id'])->
					setLogin($user['login'])->
					setRights($user['rights']);
			}
			
			return $this;
		}
	}
?>