<?php
	class User extends Singleton
	{
		const WRONG_PASSWORD	= 1;
		const WRONG_LOGIN		= 2;
		const SUCCESS_LOGIN		= 3;
		
		const CACHE_LIFE_TIME = 86400;
		
		private $id = null;
		private $login = null;
		private $rights = array();

		/**
		 * @return User
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function getId()
		{
			return $this->id;
		}
		
		protected function setId($id)
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
		
		public function addRight($rightId, $rightAlias)
		{
			$this->rights[$rightId] = $rightAlias;
			return $this;
		}
		
		protected function loadRights()
		{
			$cacheTicket = Cache::me()->createTicket()->
				setPrefix('users')->
				setKey(__CLASS__, __FUNCTION__, $this->getId())->
				setActualTime(time() + self::CACHE_LIFE_TIME)->
				restoreData();
						
			if($cacheTicket->isExpired())
			{
				$this->setRights(array());
				
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
							$this->addRight($dbRow['id'], $dbRow['alias']);
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
				
				$cacheTicket->setData($this->getRights())->storeData();
			}
			else
				$this->setRights($cacheTicket->getData());
			
			return $this;
		}
		
		public function login($login, $password)
		{
			Session::me()->start();
			Session::me()->drop('user');
			Session::me()->save();
			
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
					
					Session::me()->set(
						'user',
						array(
							'id' => $this->getId(),
							'login' => $this->getLogin(),
							'rights' => $this->getRights()
						)
					);
					Session::me()->save();
					return self::SUCCESS_LOGIN;
				}
				else return self::WRONG_PASSWORD;
			}
			else return self::WRONG_LOGIN;
		}
		
		public function onSessionStarted()
		{
			$user = Session::me()->get('user');
			if($user)
			{
				$this->
					setId($user['id'])->
					setLogin($user['login'])->
					setRights($user['rights']);
			}
			
			return $this;
		}
	}
?>