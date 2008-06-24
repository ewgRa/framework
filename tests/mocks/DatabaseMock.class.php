<?php
	class DatabaseMock
	{
		public static function create()
		{
			Mock::generate('Database', 'DatabaseTestMock');
			$database = &new DatabaseTestMock();
			Singleton::setInstance('Database', $database);
			return $database;
		}
		
		public static function drop()
		{
			Singleton::dropInstance('Database');
		}
	}
?>