<?php
	/**
	 * Класс-контроллер, реализующий View на основе XML/XSLT
	 */
	class EngineAjaxView extends AjaxView 
	{
		/**
		 * При создании класс регистрируем событие OnView и подписываемся на него как приниматель данных
		 * @return EngineAjaxView
		 */
		function __construct()
		{
			$JsHttpRequest = JsHttpRequest::getInstance( 'UTF-8' );
			EventDispatcher::RegisterCatcher( 'View', array( $this, 'ViewReceiver' ) );
		}

		/**
		 * Функция отвечает за выдачу результата работы сервера пользователю
		 * @param array $Data
		 */
		function ViewReceiver( $Data )
		{
			$this->Process( $Data );
		}
	}
?>