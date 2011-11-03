<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MysqlDatabaseInsertResult extends MysqlDatabaseResult
		implements DatabaseInsertResultInterface
	{
		private $insertedId = null;

		/**
		 * @return MysqlDatabaseInsertResult
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