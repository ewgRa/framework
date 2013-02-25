<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class DatabaseQuery implements DatabaseQueryInterface
	{
		private $query = null;
		private $values = array();

		/**
		 * @return DatabaseQuery
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return DatabaseQuery
		 */
		public function setQuery($query)
		{
			$this->query = $query;
			return $this;
		}

		public function getQuery()
		{
			return $this->query;
		}

		/**
		 * @return DatabaseQuery
		 */
		public function setValues(array $values)
		{
			$this->values = $values;
			return $this;
		}

		public function toString(
			DatabaseDialectInterface $dialect,
			DatabaseInterface $database = null
		) {
			$query = str_replace('?', '??', $this->query);
			$queryParts = explode('?', $query);
			$partsCounter = 0;

			reset($this->values);

			foreach ($queryParts as $partKey => $part) {
				if ($partsCounter % 2) {
					if (!is_null(key($this->values))) {
						$value = $this->values[key($this->values)];

						if (is_null($value))
							$part = "NULL";
						else {
							if (!($value instanceof DatabaseValue))
								$value = DatabaseValue::create($value);

							$rawValue = $value->getRawValue();

							if ($value->isEscapeNeeded())
								$rawValue = $dialect->escape($rawValue, $database);

							$part =
								is_array($rawValue)
									? "'".join("', '", $rawValue)."'"
									: ($value->isQuoteNeeded() ? "'".$rawValue."'" : $rawValue);
						}

						next($this->values);
					}
					else
						$part = "?";
				}

				$queryParts[$partKey] = $part;
				$partsCounter++;
			}

			return join('', $queryParts);
		}
	}
?>