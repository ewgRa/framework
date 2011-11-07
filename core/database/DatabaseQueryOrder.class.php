<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseQueryOrder implements DatabaseQueryOrderInterface
	{
		const ASC 	= 1;
		const DESC 	= 2;

		const NULLS_FIRST 	= 1;
		const NULLS_LAST 	= 2;

		private $field = null;
		private $sortDirection = self::ASC;

		private $nulls = self::NULLS_FIRST;

		public static function create($field)
		{
			return new self($field);
		}

		public function __construct($field)
		{
			$this->field = $field;
		}

		public function getField()
		{
			return $this->field;
		}

		public function isAsc()
		{
			return $this->sortDirection === self::ASC;
		}

		public function asc()
		{
			$this->sortDirection = self::ASC;
			return $this;
		}

		public function desc()
		{
			$this->sortDirection = self::DESC;
			return $this;
		}

		public function isNullsFirst()
		{
			return $this->nulls === self::NULLS_FIRST;
		}

		public function nullsFirst()
		{
			$this->nulls = self::NULLS_FIRST;
			return $this;
		}

		public function nullsLast()
		{
			$this->nulls = self::NULLS_LAST;
			return $this;
		}

		public function toString(DatabaseDialectInterface $dialect)
		{
			return $dialect->getOrderString($this);
		}
	}
?>