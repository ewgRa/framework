<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PostgresqlDialect extends Singleton implements DatabaseDialectInterface
	{
		/**
		 * @return PostgresqlDialect
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

			if (!is_null($count))
				$limit[] = (int)$count;

			if (!is_null($offset))
				$limit[] = (int)$offset;

			return
				count($limit)
					? ' LIMIT '.join(' OFFSET ', $limit)
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
						? pg_escape_string(
							$database->getLinkIdentifier(),
							$variable
						)
						: pg_escape_string($variable);
			}

			return $variable;
		}

		public function escapeTable($table, DatabaseInterface $database = null)
		{
			return '"'.$table.'"';
		}

		public function escapeField($field, DatabaseInterface $database = null)
		{
			return '"'.$field.'"';
		}
	}
?>