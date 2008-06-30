<?php
	/* $Id$ */

	class SessionMock
	{
		public static function create()
		{
			Mock::generate('Session', 'SessionTestMock');
			$session = &new SessionTestMock();
			Singleton::setInstance('Session', $session);
			return $session;
		}
		
		public static function drop()
		{
			Singleton::dropInstance('Session');
		}
	}
?>