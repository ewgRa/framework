<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface DatabaseInterface
	{
		public static function create();

		public function connect();
		
		public function selectCharset($charset = 'utf8');
		
		public function selectDatabase($databaseName = null);
		
		public function disconnect();
		
		public function query($query, array $values = array());

		public function recordCount($resource);

		public function fetchArray($resource);
		
		public function dataSeek($resource, $row);
		
		public function resourceToArray($resource, $field = null);
		
		public function getLimit($count = null, $from = null);
		
		public function getInsertedId();
		
		public function escape($variable);

		public function getError();

		public function getLastQuery();
	}
?>