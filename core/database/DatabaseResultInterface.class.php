<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface DatabaseResultInterface
	{
		public static function create();

		public function recordCount();

		public function fetchArray();
		
		public function fetchList($field = null, $keyField = null);
		
		public function dataSeek($row);
		
	}
?>