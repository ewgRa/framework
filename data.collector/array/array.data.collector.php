<?php
	/**
	 * Data Collector, использующий для хранения информации массивы
	 */
	class ArrayDataCollector
	{
		/**
		 * Переменная, хранящая информацию, которую записывают в Data Collector
		 * @var array
		 */
		var $Data = array();

		/**
		 * Функция возвращает накопленную информацию
		 * @return array
		 */
		function GetData()
		{
			return $this->Data;
		}

		/**
		 * Запись информации в Data Collector
		 * @param array $Data - записываемые данные
		 * @param string $Prefix - имя ключа ассоциативного массива
		 * @param array $Array - массив, в который необходимо добавить данные
		 * @return array
		 */
		function Set( $Data, $Prefix = null, $Array = null )
		{
			$PrefixAppend = array();
			if( is_array( $Prefix ) )
			{
				$RealPrefix = array_shift( $Prefix );
				$SubPrefix = array_shift( $Prefix );
				foreach( $Prefix as $k => $PAppend )
				{
					$PrefixAppend[$k] = $PAppend;
				}
				$Prefix = $RealPrefix;
			}
			else 
			{
				$SubPrefix = null;
			}
			# Если массив не передали - берем "root" массив
			if( is_null( $Array ) )	$Array = &$this->Data;

			# Создаем контейнер хранения, или проверяем есть ли у нас уже такой контейнер
			if( array_key_exists( $Prefix, $Array ) )
			{
				if( is_null( $SubPrefix ) || array_key_exists( $SubPrefix, $Array[$Prefix] ) )
				{
					# Уникальность нарушена
					throw new ExceptionMap::$Classes['ArrayDataCollectorException']( 'Non-unique ArrayDataCollector set with prefix: "' . $Prefix. '" and subprefix: "' . $SubPrefix . '"', ArrayDataCollectorException::UNIQUE_FAILED );
				}
				else $Array[$Prefix][$SubPrefix] = array();
			}
			else
			{
				$Array[$Prefix] = array();
				if( !is_null( $SubPrefix ) )
					$Array[$Prefix][$SubPrefix] = array();
			}
			
			if( !is_null( $SubPrefix ) )
			{
				$Array[$Prefix][$SubPrefix] = $Data;
				foreach( $PrefixAppend as $k => $PAppend )
				{
					if( !array_key_exists( $k, $Array[$Prefix][$SubPrefix] ) )
					{
						$Array[$Prefix][$SubPrefix][$k] = $PAppend;
					}
				}
			}
			else 
			{
				$Array[$Prefix] = $Data;
				foreach( $PrefixAppend as $k => $PAppend )
				{
					if( !array_key_exists( $k, $Array[$Prefix] ) )
					{
						$Array[$Prefix][$k] = $PAppend;
					}
				}
			}

			return $Array;
		}
	}
	
/*
	$DC = new ArrayDataCollector();
	$DC->Set( array( 2, 3 ), array( 'CATALOG_MODULE', 'simple_items' ) );
	$DC->Set( array(1), array( 'CATALOG_MODULE', 's' ) );
//	$DC->Set( array( 2 ), array( 'CATALOG_MODULE2', 'simple_items' ) );
	$DC->Set( array( 2 ), 'test' );
//	$DC->Set( array( 3 ), 'test' );
	print_r( $DC->GetData() );
*/
?>