<?php
	/**
	 * Класс фабрика для DataCollector'ов
	 */
	class DataCollector extends Factory
	{
		/**
		 * Функция формирует и возвращает необходимый DataCollector
		 * @param string $ViewType - тип загружаемого DataCollector'a
		 * @return mixed
		 */
		public static function Make( $ViewType )
		{
			$ClassName = self::CompileClassName( $ViewType );
			return new $ClassName;
		}
		
		/**
		 * Функция на основе входного имени DataCollector'а возвращает какой имя класса, отвечающего за реализацию данного типа DataCollector'а
		 * @param string $ClassName
		 * @return string
		 */
		public static function CompileClassName( $ClassName )
		{
			switch( $ClassName )
			{
				case 'XSLT':
					return 'EngineXMLDataCollector';
				break;
				case 'AJAX':
					return 'EngineArrayDataCollector';
				break;
				case 'JSON':
					return 'EngineArrayDataCollector';
				break;
				case 'Native':
					return 'EngineArrayDataCollector';
				break;
				case 'Excel':
					return 'EngineArrayDataCollector';
				break;
				case 'Redirect':
					return 'EngineRedirectDataCollector';
				break;
			}			
		}
	}
?>