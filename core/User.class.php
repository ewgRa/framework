<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class User
	{
		const WRONG_PASSWORD	= 1;
		const WRONG_LOGIN		= 2;
		const SUCCESS_LOGIN		= 3;
		
		/**
		 * @var UserDA
		 */
		private $da = null;
		
		private $id		= null;
		private $login	= null;
		private $rights	= array();
		private $session = null;

		/**
		 * @return User
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return UserDA
		 */
		public function da()
		{
			if(!$this->da)
				$this->da = UserDA::create();
				
			return $this->da;
		}
		
		public function getId()
		{
			return $this->id;
		}
		
		/**
		 * @return User
		 */
		protected function setId($id)
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
		
		public function getSession()
		{
			return $this->session;
		}
		
		/**
		 * @return BaseSession
		 */
		private function setSession(BaseSession $session)
		{
			$this->session = $session;
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
			
			$checkPassword = $this->da()->checkLogin($login, $password);

			if($checkPassword)
			{
				if($checkPassword['verify_password'])
				{
					$this->setId($checkPassword['id']);
					$this->setLogin($checkPassword['login']);
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
		protected function loadRights()
		{
			$this->dropRights();
			
			if($this->getId())
			{
				$rights = $this->da()->loadRights($this->getId());
				
				while($rights)
				{
					$inheritanceId = array();

					foreach($rights as $right)
					{
						if($this->hasRight($right['alias']))
							continue;

						$inheritanceId[] = $right['id'];
						$this->addRight($right['id'], $right['alias']);
					}

					$rights = $this->da()->loadInheritanceRights($inheritanceId);
				}
			}
			
			return $this;
		}
	}
?>