<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MysqlDialect extends Singleton implements DatabaseDialectInterface
	{
		/**
		 * @return MysqlDialect
		 */
		public static function me()
		{
			return Singleton::getInstance(__CLASS__);
		}

		public function getLimit($count = null, $from = null)
		{
			if (!is_null($from) && $from < 0)
				$from = 0;
			
			if (!is_null($count) && $count < 0)
				$count = 0;
			
			$limit = array();
			
			if (!is_null($from))
				$limit[] = (int)$from;
			
			if (!is_null($count))
				$limit[] = (int)$count;
			
			return
				count($limit)
					? ' LIMIT ' . join(', ', $limit)
					: '';
		}

		public function escape($variable, DatabaseInterface $database = null)
		{
			if (is_array($variable)) {
				foreach ($variable as &$value)
					$value = $this->{__FUNCTION__}($value, $database);
			} else {
				if ($database && !$database->isConnected())
					$database->connect()->selectDatabase()->selectCharset();

				$variable =
					$database
						? mysql_real_escape_string(
							$variable, $database->getLinkIdentifier()
						)
						: mysql_real_escape_string($variable);
			}
			
			return $variable;
		}

		public function escapeTable($table, DatabaseInterface $database = null)
		{
			return '`'.$table.'`';
		}
	}
?>