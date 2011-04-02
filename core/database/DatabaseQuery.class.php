<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseQuery implements DatabaseQueryInterface
	{
		private $query = null;
		private $values = array();

		public static function create()
		{
			return new self;
		}

		public function setQuery($query)
		{
			$this->query = $query;
			return $this;
		}

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
							$value = $dialect->escape($value, $database);

							$part =
								is_array($value)
									? "'".join("', '", $value)."'"
									: "'".$value."'";
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