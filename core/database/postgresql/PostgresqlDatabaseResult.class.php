<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PostgresqlDatabaseResult extends BaseDatabaseResult
	{
		/**
		 * @return PostgresqlDatabaseResult
		 */
		public static function create()
		{
			return new self;
		}

		public function recordCount()
		{
			return pg_num_rows($this->getResource());
		}

		public function fetchRow()
		{
			return pg_fetch_assoc($this->getResource());
		}

		/**
		 * @return MysqlDatabaseResult
		 */
		public function dataSeek($row)
		{
			pg_result_seek($this->getResource(), $row - 1);
			return $this;
		}
	}
?>