<?php
	/* $Id: AttachedAliases.class.php 174 2009-03-13 06:53:04Z ewgraf $ */

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
				" = '" . $db->escape($expression->getValue()) . "'";
		}
	}
?>