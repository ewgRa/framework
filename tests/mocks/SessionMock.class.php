<?php
	/* $Id$ */

	class SessionMock
	{
		private static $savedSession = null;
		
		public static function create()
		{
			if(Singleton::hasInstance('Session'))
				self::$savedSession = serialize(Session::me());
			
			Mock::generate('Session', 'SessionTestMock');
			$session = &new SessionTestMock();
			
			Singleton::setInstance('Session', $session);
			
			return $session;
		}
		
		public static function drop()
		{
			if(self::$savedSession)
			{
				Singleton::setInstance(
					'Session',
					unserialize(self::$savedSession)
				);
				
				self::$savedSession = null;
			}
			else
				Singleton::dropInstance('Session');
		}
	}
?>