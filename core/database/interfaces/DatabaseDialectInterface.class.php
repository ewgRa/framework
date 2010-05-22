<?php
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
		public function quoteTable($table, DatabaseInterface $database = null);
	}
?>