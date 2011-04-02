<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface DatabaseQueryInterface
	{
		/**
		 * @var $database needed for escaping, depended on charset
		 * 					(e.g. mysql_real_escape_string)
		 */
		public function toString(
			DatabaseDialectInterface $dialect,
			DatabaseInterface $database = null
		);
	}
?>