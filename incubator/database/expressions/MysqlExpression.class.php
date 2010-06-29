<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MysqlExpression extends Singleton
	{
		/**
		 * @return MysqlExpression
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		/**
		 * @return MysqlInExpression
		 */
		public function in($field, $values)
		{
			return new MysqlInExpression($field, $values);
		}

		/**
		 * @return MysqlEqExpression
		 */
		public function eq($field, $value)
		{
			return new MysqlEqExpression($field, $value);
		}
	}
?>