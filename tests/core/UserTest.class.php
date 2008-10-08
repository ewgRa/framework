<?php
	/* $Id$ */

	class UserTest extends UnitTestCase
	{
		private $savedUser = null;
		
		public function setUp()
		{
			$this->savedUser = serialize(User::me());
			Singleton::dropInstance('User');
			
			DatabaseMock::create();
			SessionMock::create();
		}
		
		public function tearDown()
		{
			Singleton::setInstance('User', unserialize($this->savedUser));
			
			DatabaseMock::drop();
			SessionMock::drop();
		}
		
		public function testLoadRights()
		{
			MyTestUser::ftSetId(10);
			
			Database::me()->setReturnValueAt(0, 'recordCount', true);
			Database::me()->setReturnValueAt(1, 'recordCount', true);
			Database::me()->setReturnValueAt(2, 'recordCount', true);
			
			Database::me()->setReturnValueAt(
				0,
				'fetchArray',
				array('id' => 1, 'alias' => 'root')
			);
			
			Database::me()->setReturnValueAt(
				2,
				'fetchArray',
				array('id' => 2, 'alias' => 'demo')
			);
			
			$this->assertEqual(
				MyTestUser::ftLoadRights()->getRights(),
				array(1 => 'root', 2 => 'demo')
			);
		}
	}
	
	class MyTestUser extends User
	{
		public static function ftSetId($id)
		{
			return self::me()->setId($id);
		}

		public static function ftLoadRights()
		{
			return self::me()->loadRights();
		}
	}
?>