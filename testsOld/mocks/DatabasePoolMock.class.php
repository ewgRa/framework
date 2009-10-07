<?php
	/* $Id$ */

	class DatabasePoolMock
	{
		public static function create($realization = 'MysqlDatabase')
		{
			Mock::generate($realization, 'DatabasePoolTestMock');
			$database = &new DatabasePoolTestMock();
			
			return $database;
		}
	}
?>