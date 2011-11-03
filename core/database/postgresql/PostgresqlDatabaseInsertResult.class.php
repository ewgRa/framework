<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PostgresqlDatabaseInsertResult extends PostgresqlDatabaseResult
		implements DatabaseInsertResultInterface
	{
		private $insertedId = null;

		/**
		 * @return PostgresqlDatabaseInsertResult
		 */
		public static function create()
		{
			return new self;
		}

		public function setInsertedId($insertedId)
		{
			$this->insertedId = $insertedId;
			return $this;
		}

		public function getInsertedId()
		{
			return $this->insertedId;
		}
	}
?>