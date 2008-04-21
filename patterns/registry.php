<?php
	/**
	 * Паттерн Registry
	 * Сохраняет и извлекает экземпляры объектов
	 * Реализация простейшая, есть более сложные реализации, пока что хватает этого 
	 */
	class Registry
	{
		/**
		 * @var array
		 */
		private static $Objects = array();
		
		/**
		 * @param string $ObjectName    
		 * @param object $Object    
		 * @return boolean
		 */
		public function Set( $ObjectName, $Object )
		{
			self::$Objects[$ObjectName] = $Object;
		}
		
		/**
		 * @param string $ObjectName    
		 * @return object
		 */
		public function Get( $ObjectName, $autoCreate = true )
		{
			if( !array_key_exists( $ObjectName, self::$Objects ) && $autoCreate )
			{
				self::$Objects[$ObjectName] = new $ObjectName;
			}
			if( array_key_exists( $ObjectName, self::$Objects ) )
			{
				return self::$Objects[$ObjectName];
			}
		}
		
		/**
		 * Очищаем объекты
		 */
		public function Clear()
		{
			self::$Objects = array();
		}
	}

?>