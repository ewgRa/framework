<?php
	class ExceptionMap
	{
		static $Classes = array(
			'PageExceptionClass' => 'PageException',
			'DatabaseExceptionClass' => 'DatabaseException',
			'ArrayDataCollectorExceptionClass' => 'ArrayDataCollectorException'
		);
		
		
		function set( $Alias, $ClassName )
		{
			self::$Classes[$Alias] = $ClassName;
		}
	}
?>