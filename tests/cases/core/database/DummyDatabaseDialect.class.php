<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DummyDatabaseDialect extends \ewgraFramework\Singleton
		implements \ewgraFramework\DatabaseDialectInterface
	{
		/**
		 * @return DummyDatabaseDialect
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

		public function escape($variable, \ewgraFramework\DatabaseInterface $database = null)
		{
			if (is_array($variable)) {
				foreach ($variable as &$value)
					$value = $this->{__FUNCTION__}($value, $database);
			} else
				$variable = '|'.$variable.'|';

			return $variable;
		}

		public function escapeTable($table, \ewgraFramework\DatabaseInterface $database = null)
		{
			return '|'.$table.'|';
		}

		public function escapeField($fied, \ewgraFramework\DatabaseInterface $database = null)
		{
			return '|'.$field.'|';
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
			return \ewgraFramework\DatabaseQueryOrder::create($field);
		}

		public function getOrderString(\ewgraFramework\DatabaseQueryOrderInterface $order)
		{
			return
				$this->escapeField($order->getField()).($order->isAsc() ? null : ' DESC')
				.(
					$order->isNullsFirst()
						? ' NULLS FIRST'
						: ' NULLS LAST'
				);
		}
	}
?>