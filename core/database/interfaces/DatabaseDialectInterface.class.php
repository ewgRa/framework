<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface DatabaseDialectInterface
	{
		public function getLimit($count, $offset = null);

		/**
		 * @var $database needed for escaping, depended on charset
		 * 					(e.g. mysql_real_escape_string)
		 */
		public function escape($variable, DatabaseInterface $database = null);

		/**
		 * @var $database needed for escaping, depended on charset
		 * 					(e.g. mysql_real_escape_string)
		 */
		public function escapeTable($table);

		/**
		 * @var $database needed for escaping, depended on charset
		 * 					(e.g. mysql_real_escape_string)
		 */
		public function escapeField($field);

		public function condition($expression, $then, $else);

		/**
		 * @return DatabaseQueryOrderInterface
		 */
		public function createOrder($field);

		public function getOrderString(DatabaseQueryOrderInterface $order);
	}
?>