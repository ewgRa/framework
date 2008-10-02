<?php
	/* $Id$ */

	class User extends Singleton
	{
		const WRONG_PASSWORD	= 1;
		const WRONG_LOGIN		= 2;
		const SUCCESS_LOGIN		= 3;
		
		private $id		= null;
		private $login	= null;
		private $rights	= array();

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
		
		/**
		 * @return User
		 */
		private function setId($id)
		{
			$this->id = $id;
			return $this;
		}
		
		public function getLogin()
		{
			return $this->login;
		}
		
		/**
		 * @return User
		 */
		private function setLogin($login)
		{
			$this->login = $login;
			return $this;
		}
		
		public function hasRight($alias)
		{
			return isset($this->rights[$alias]);
		}
		
		public function getRights()
		{
			return $this->rights;
		}
		
		/**
		 * @return User
		 */
		public function dropRights()
		{
			$this->rights = array();
			return $this;
		}
		
		/**
		 * @return User
		 */
		public function setRights($rights)
		{
			$this->rights = $rights;
			return $this;
		}
		
		/**
		 * @return User
		 */
		public function addRight($rightId, $rightAlias)
		{
			$this->rights[$rightId] = $rightAlias;
			return $this;
		}
		
		public function login($login, $password)
		{
			Session::me()->start();
			Session::me()->drop('user');
			Session::me()->save();
			
			$dbQuery = "
				SELECT *, password = MD5( ? ) as verify_password
					FROM " . Database::me()->getTable('Users') . "
				WHERE login = ?
			";

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

		// FIXME: move this method to anywhere :)
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

		/**
		 * @return User
		 */
		private function loadRights()
		{
			$this->dropRights();
			
			if($this->getId())
			{
				$dbQuery = "
					SELECT t1.* FROM " . Database::me()->getTable('Rights') . " t1
					INNER JOIN " . Database::me()->getTable('UsersRights_ref') . " t2
						ON ( t1.id = t2.right_id AND t2.user_id = ? )
				";

				$dbResult = Database::me()->query(
					$dbQuery, array($this->getId())
				);
				
				while(Database::me()->recordCount($dbResult))
				{
					$inheritanceId = array();

					while($dbRow = Database::me()->fetchArray($dbResult))
					{
						if($this->hasRight($dbRow['alias']))
							continue;

						$inheritanceId[] = $dbRow['id'];
						$this->addRight($dbRow['id'], $dbRow['alias']);
					}

					$dbQuery = "
						SELECT t1.* FROM " . Database::me()->getTable('Rights') . " t1
						INNER JOIN " . Database::me()->getTable('Rights_inheritance') . " t2
							ON ( t1.id = t2.child_right_id AND t2.right_id IN( ? ) )
					";

					$dbResult = Database::me()->query(
						$dbQuery,
						array($inheritanceId)
					);
				}
			}
			
			return $this;
		}
	}
?>