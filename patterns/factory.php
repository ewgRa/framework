<?php
	/**
	 * Паттерн фабрика на основе переданного имени класса, фозвращает его экземпляр
	 */
	abstract class Factory
	{
	    public static function Make( $ClassName )
	    {
            return new $ClassName;
	    }
	}
?>