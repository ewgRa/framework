<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MysqlDialect extends Singleton implements DatabaseDialectInterface
	{
		/**
		 * @return MysqlDialect
		 * method needed for methods hinting
		 */
		public static function me()
		{
			return parent::me();
		}

		public function getLimit($count, $offset = null)
		{
			if (!is_null($offset) && $offset < 0)
				$offset = 0;

			if (!is_null($count) && $count < 0)
				$count = 0;

			$limit = array();

			if (!is_null($offset))
				$limit[] = (int)$offset;

			if (!is_null($count))
				$limit[] = (int)$count;

			return
				count($limit)
					? ' LIMIT '.join(', ', $limit)
					: '';
		}

		public function escape($variable, DatabaseInterface $database = null)
		{
			if (is_array($variable)) {
				foreach ($variable as &$value)
					$value = $this->{__FUNCTION__}($value, $database);
			} else {
				if ($database && !$database->isConnected())
					$database->connect();

				$variable =
					$database
						? mysql_real_escape_string(
							$variable, $database->getLinkIdentifier()
						)
						: mysql_real_escape_string($variable);
			}

			return $variable;
		}

		public function escapeTable($table)
		{
			return '`'.$table.'`';
		}

		public function escapeField($field)
		{
			return '`'.$field.'`';
		}

		public function condition($expression, $then, $else)
		{
			return 'IF('.$expression.', '.$then.', '.$else.')';
		}

		/**
		 * @return DatabaseQueryOrderInterface
		 */
		public function createOrder($field)
		{
			return DatabaseQueryOrder::create($field);
		}

		public function getOrderString(DatabaseQueryOrderInterface $order)
		{
			return
				(
					$order->isNullsFirst()
						? 'IF(ISNULL('.$this->escapeField($order->getField()).'), 0, 1), '
						: 'IF(ISNULL('.$this->escapeField($order->getField()).'), 1, 0), '
				)
				.$this->escapeField($order->getField()).($order->isAsc() ? null : ' DESC');
		}
	}
?>