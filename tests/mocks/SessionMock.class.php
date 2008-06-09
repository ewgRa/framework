<?php
	class SessionMock
	{
		public static function create()
		{
			Mock::generate('Session', 'SessionTestMock');
			$session = &new SessionTestMock();
			MySession::setInstance($session);
			return $session;
		}
		
		public static function drop()
		{
			MySession::dropInstance();
		}
	}
?>