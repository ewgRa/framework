<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface DatabaseDialectInterface
	{
		public function getLimit($count = null, $from = null);
		
		/**
		 * @var $database needed for escaping, depended on charset
		 * 					(e.g. mysql_real_escape_string)
		 */
		public function escape($variable, DatabaseInterface $database = null);

		/**
		 * @var $database needed for escaping, depended on charset
		 * 					(e.g. mysql_real_escape_string)
		 */
		public function escapeTable($table, DatabaseInterface $database = null);
	}
?>