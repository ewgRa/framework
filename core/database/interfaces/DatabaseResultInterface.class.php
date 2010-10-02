<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface DatabaseResultInterface
	{
		public static function create();

		public function recordCount();

		public function fetchRow();
		
		public function fetchList();
		
		/**
		 * @throws MissingArgumentException
		 */
		public function fetchFieldList($field, $keyField = null);
		
		public function dataSeek($row);
	}
?>