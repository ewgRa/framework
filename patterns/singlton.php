<?php
	/**
	 * Паттерн "Одиночка", от него можно просто унаследоваться
	 */
	class Singlton
	{
		private static $instance = false;
		function getInstance()
		{
			if( !self::$instance )
			{
				$Reflection = new ReflectionClass( get_class( self ) );
				$fargs = func_get_args();
				self::$instance = call_user_func_array( array( &$Reflection, 'newInstance' ), $fargs );
			}
			return self::$instance;
		}
	}
?>