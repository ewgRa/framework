<?php
	/* $Id$ */

	class DatabaseMock
	{
		private static $savedDatabase = null;
		
		public static function create($realization = 'MysqlDatabase')
		{
			if(Singleton::hasInstance('Database'))
				self::$savedDatabase = serialize(Database::me());
			
			Mock::generate($realization, 'DatabaseTestMock');
			$database = &new DatabaseTestMock();
			
			Singleton::setInstance('Database', $database);
			
			return $database;
		}
		
		public static function drop()
		{
			if(self::$savedDatabase)
			{
				Singleton::setInstance(
					'Database',
					unserialize(self::$savedDatabase)
				);
				
				self::$savedDatabase = null;
			}
			else
				Singleton::dropInstance('Database');
		}
	}
?>