<?php
	/* $Id: MysqlDatabase.class.php 174 2009-03-13 06:53:04Z ewgraf $ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SqlExpression
	{
		/**
		 * @return SqlInExpression
		 */
		public static function in($field, $values)
		{
			return new SqlInExpression($field, $values);
		}

		/**
		 * @return SqlEqExpression
		 */
		public static function eq($field, $value)
		{
			return new SqlEqExpression($field, $value);
		}
	}
?>