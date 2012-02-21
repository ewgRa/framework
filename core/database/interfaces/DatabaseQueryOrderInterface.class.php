<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface DatabaseQueryOrderInterface
	{
		public static function create($field, $table = null);

		public function getField();

		public function getTable();

		public function isAsc();

		public function asc();

		public function desc();

		public function isNullsFirst();

		public function nullsFirst();

		public function nullsLast();

		public function toString(DatabaseDialectInterface $dialect);
	}
?>