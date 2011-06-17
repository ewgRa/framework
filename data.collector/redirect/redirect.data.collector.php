<?php
	/**
	 * Класс реализующий DataCollector для RedirectView
	 */
	class RedirectDataCollector
	{
		/**
		 * Переменная, которая хранит куда перенаправляться
		 * @var array
		 */
		var $RedirectURI = array();

		function __construct()
		{
		}
		
		/**
		 * Функция возвращает данные, которые хранит DataCollector
		 * @return array
		 */
		function GetData()
		{
			return $this->RedirectURI;
		}

		/**
		 * Запись данных в датаколлектор
		 * @param string $RequestURI
		 * @return null
		 */
		function Set( $RequestURI )
		{
			$this->RedirectURI[] = $RequestURI;
		}
	}
?>