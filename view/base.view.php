<?php
	/**
	 * Базовый View класс, реализующий самые общие функции View
	 */
	abstract class BaseView
	{
		/**
		 * Параметры для View. Например, какие файлы включать необходимо в шаблон и т.п.
		 * @var array
		 */
		public $Settings = array();
		
		/**
		 * Заголовки, отдаваемые в поток браузера
		 * @var array
		 */
		public $Headers = array(
			'Content-type' => 'text/html;charset=utf-8',
			'Cache-Control' => 'no-store, no-cache, must-revalidate',
			'Pragma' => 'no-cache'
		);

		public $Result = '';
		
		function __construct()
		{
			$ProjectConfig = Config::getOption( 'Project' );
			$this->Headers['Content-type'] = 'text/html;charset=' . $ProjectConfig['Charset'];
			$this->Headers['Last-Modified'] = gmdate("D, d M Y H:i:s");
		}

		/**
		 * Вывод результатов в поток браузера
		 * @param mixed $Data
		 */
		abstract function Process( $Data );
		
		abstract function Shutdown();
		
		/**
		 * Вывод заголовков
		 */
		function OutputHeaders()
		{
			foreach ( $this->Headers as $k => $v )
			{
				if( is_numeric( $k ) )
				{
					header( $v );	
				}
				else header( "{$k}: {$v}" );
			}			
		}
	}
	
	
?>