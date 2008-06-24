<?php
	class MyTestConfig extends Config
	{
		public static function ftReplaceVariables($variables)
		{
			return self::me()->replaceVariables($variables);
		}

		public static function ftRegisterConstants()
		{
			return self::me()->registerConstants();
		}
	}
?>