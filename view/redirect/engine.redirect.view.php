<?php
	/**
	 * Класс-контроллер, реализующий View на основе Redirect'a
	 */
	class EngineRedirectView extends RedirectView 
	{
		/**
		 * При создании класс регистрируем событие OnView и подписываемся на него как приниматель данных
		 * @return EngineRedirectView
		 */
		function __construct()
		{
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