<?php
	/**
	 * Класс-фабрика для View, на основе поступающего типа View возвращает класс, который отвечает за View
	 *
	 */
	class View extends Factory
	{
		/**
		 * Функция, генерирующая необходимый класс
		 * @param string $ViewType - тип View
		 * @return mixed
		 */
		public static function Make( $ViewType )
		{
			$ClassName = self::CompileClassName( $ViewType );
			return new $ClassName;
		}
		
		/**
		 * Функция на основе входного имени View'а возвращает какой имя класса, отвечающего за реализацию данного типа View
		 * @param string $ClassName
		 * @return string
		 */
		public static function CompileClassName( $ClassName )
		{
			switch( $ClassName )
			{
				case 'XSLT':
					return 'EngineXSLTView';
				break;
				case 'AJAX':
					return 'EngineAjaxView';
				break;
				case 'JSON':
					return 'EngineJsonView';
				break;
				case 'Excel':
					return 'EngineExcelView';
				break;
				case 'Redirect':
					return 'EngineRedirectView';
				break;
				case 'Native':
					return 'EngineNativeView';
				break;				
			}			
		}
	}
?>