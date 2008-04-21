<?php
	/**
	 * Класс, реализующий View на основе Redirect'а
	 */
	class RedirectView extends BaseView
	{
		/**
		 * Функция обработки и вывода результата XSLT преобразования
		 * @param Array
		 */
		function Process( $RedirectURI ) 
		{
			# Выводим заголовки
			$this->Headers['Location'] = array_shift( $RedirectURI );
		}
		
		function Shutdown()
		{
			$this->OutputHeaders();
		}
	}
?>