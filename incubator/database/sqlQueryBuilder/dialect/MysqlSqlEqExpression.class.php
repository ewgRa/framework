<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MysqlSqlEqExpression
	{
		public static function toString(
			SqlEqExpression $expression,
			MysqlDatabase $db
		) {
			return
				MysqlSqlBuilder::toString($expression->getField()).
				" = '".$db->escape($expression->getValue())."'";
		}
	}
?>