<?php
	class DatabaseMock
	{
		public static function create()
		{
			Mock::generate('Database', 'DatabaseTestMock');
			$database = &new DatabaseTestMock();
			MyTestDatabase::setInstance($database);
			return $database;
		}
		
		public static function drop()
		{
			MyTestDatabase::dropInstance();
		}
	}
?>