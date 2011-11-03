<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseInsertQuery extends DatabaseQuery implements DatabaseInsertQueryInterface
	{
		private $primaryField = null;

		/**
		 * @return DatabaseInsertQuery
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return DatabaseInsertQuery
		 */
		public function setPrimaryField($primaryField)
		{
			$this->primaryField = $primaryField;
			return $this;
		}

		public function getPrimaryField()
		{
			return $this->primaryField;
		}
	}
?>