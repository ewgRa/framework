<?php
	class DatabaseMock
	{
		public static function create()
		{
			Mock::generate('Database', 'DatabaseTestMock');
			$database = &new DatabaseTestMock();
			MyDatabase::setInstance($database);
			return $database;
		}
		
		public static function drop()
		{
			MyDatabase::dropInstance();
		}
	}
?>