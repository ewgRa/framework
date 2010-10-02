<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MysqlDatabaseResult extends BaseDatabaseResult
	{
		/**
		 * @return MysqlDatabaseResult
		 */
		public static function create()
		{
			return new self;
		}
		
		public function recordCount()
		{
			return mysql_numrows($this->getResource());
		}

		public function fetchRow()
		{
			return mysql_fetch_assoc($this->getResource());
		}

		/**
		 * @return MysqlDatabaseResult
		 */
		public function dataSeek($row)
		{
			mysql_data_seek($this->getResource(), $row - 1);
			return $this;
		}
	}
?>