<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * // FIXME: tested?
	*/
	final class UserDA extends DatabaseRequester
	{
		/**
		 * @return UserDA
		 */
		public static function create()
		{
			return new self;
		}
		
		public function checkLogin($login, $password)
		{
			$result = null;
			
			$dbQuery = '
				SELECT *, password = MD5( ? ) as verify_password
					FROM ' . $this->db()->getTable('Users') . '
				WHERE login = ?
			';

			$dbResult = $this->db()->query($dbQuery, array($password, $login));

			if($this->db()->recordCount($dbResult))
				$result = $this->db()->fetchArray($dbResult);
			
			return $result;
		}

		public function loadRights($userId)
		{
			$result = null;
			
			$dbQuery = '
				SELECT t1.* FROM ' . $this->db()->getTable('Rights') . ' t1
				INNER JOIN ' . $this->db()->getTable('UsersRights_ref') . ' t2
					ON ( t1.id = t2.right_id AND t2.user_id = ? )
			';

			$dbResult = $this->db()->query($dbQuery, array($userId));
			
			if($this->db()->recordCount($dbResult))
				$result = $this->db()->resourceToArray($dbResult);
			
			return $result;
		}
		
		public function loadInheritanceRights($inheritanceId)
		{
			$result = null;
			
			$dbQuery = '
				SELECT t1.* FROM ' . $this->db()->getTable('Rights') . ' t1
				INNER JOIN ' . $this->db()->getTable('Rights_inheritance') . ' t2
					ON ( t1.id = t2.child_right_id AND t2.right_id IN( ? ) )
			';

			$dbResult = $this->db()->query(
				$dbQuery,
				array($inheritanceId)
			);
			
			if($this->db()->recordCount($dbResult))
				$result = $this->db()->resourceToArray($dbResult);
			
			return $result;
		}
	}
?>