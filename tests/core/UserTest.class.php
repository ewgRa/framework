<?php
	/* $Id$ */

	class UserTest extends UnitTestCase
	{
		public function setUp()
		{
			DatabaseMock::create();
			CacheMock::create();
			SessionMock::create();
			Singleton::dropInstance('User');
		}
		
		public function tearDown()
		{
			DatabaseMock::drop();
			CacheMock::drop();
			SessionMock::drop();
			Singleton::dropInstance('User');
		}
		
		public function testLoadRights()
		{
			$cacheTicketMock = CacheTicketMock::create();
			$cacheTicketMock->setReturnValue('isExpired', true);
			
			Cache::me()->setReturnValue('createTicket', $cacheTicketMock);
			
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
			
			Database::me()->setReturnValueAt(
				4,
				'fetchArray',
				array('id' => 2, 'alias' => 'demo')
			);
			
			$this->assertEqual(
				MyTestUser::ftLoadRights()->getRights(),
				array(1 => 'root', 2 => 'demo')
			);
		}
	}
?>