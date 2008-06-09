<?php
	class SessionMock
	{
		public static function create()
		{
			Mock::generate('Session', 'SessionTestMock');
			$session = &new SessionTestMock();
			MyTestSession::setInstance($session);
			return $session;
		}
		
		public static function drop()
		{
			MyTestSession::dropInstance();
		}
	}
?>