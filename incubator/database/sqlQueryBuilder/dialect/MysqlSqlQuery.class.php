<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MysqlSqlQuery
	{
		public static function toString(SqlQuery $query, MysqlDatabase $db)
		{
			return '
				SELECT '.MysqlSqlBuilder::toString($query->getFields(), $db).'
				FROM `'.MysqlSqlBuilder::toString($query->getFrom(), $db).'`
				WHERE '.MysqlSqlBuilder::toString($query->getWhere(), $db).'
			';
		}
	}
?>