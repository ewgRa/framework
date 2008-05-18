<?php
	// tested?
	class Assert
	{
		public static function notNull($variable, $message)
		{
			if(is_null($variable))
			{
				throw new Exception($message);
			}
		}
	}
?>
