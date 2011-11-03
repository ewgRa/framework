<?php
	namespace ewgraFramework\tests;

	/**
	 * Generated by meta builder, you can edit this class
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class TestDatabaseRequester extends \ewgraFramework\Singleton
	{
		protected $tableAlias	= null;

		/**
		 * @return TestDatabaseRequester
		 * method needed for methods hinting
		 */
		public static function me()
		{
			return parent::me();
		}

		public function getTable()
		{
			\ewgraFramework\Assert::isNotNull($this->tableAlias);

			return $this->escapeTable($this->tableAlias);
		}

		/**
		 * @return \ewgraFramework\BaseDatabase
		 */
		public function db()
		{
			return $this->pool;
		}

		public function setPool(DatabaseInterface $pool)
		{
			$this->pool = $pool;
			return $this;
		}

		public function escapeTable($table)
		{
			return
				$this->db()->getDialect()->
				escapeTable($table, $this->db());
		}

		public function dropCache()
		{
			return $this;
		}

		public function getCachedByQuery(\ewgraFramework\DatabaseQueryInterface $dbQuery)
		{
			return $this->getByQuery($dbQuery);
		}

		public function getByQuery(\ewgraFramework\DatabaseQueryInterface $dbQuery)
		{
			$result = $this->getCustomByQuery($dbQuery);

			if ($result)
				$result = $this->build($result);

			return $result;
		}

		public function getCustomByQuery(\ewgraFramework\DatabaseQueryInterface $dbQuery)
		{
			$result = null;

			$dbResult = $this->db()->query($dbQuery);

			if ($dbResult->recordCount()) {
				\ewgraFramework\Assert::isEqual(
					$dbResult->recordCount(),
					1,
					'query returned more than one row'
				);

				$result = $dbResult->fetchRow();
			}

			return $result;
		}
	}
?>