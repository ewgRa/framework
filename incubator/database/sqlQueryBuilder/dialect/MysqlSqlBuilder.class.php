<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MysqlSqlBuilder
	{
		public static function toString($something, MysqlDatabase $db)
		{
			if (!is_object($something))
				return $something;
				
			$class = 'Mysql'.get_class($something);
			
			return
				call_user_func_array(
					array($class, 'toString'),
					array($something, $db)
				);
		}
	}
?>