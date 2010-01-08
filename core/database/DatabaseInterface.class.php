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

		public function queryNull($query, array $values = array());
		
		public function getLimit($count = null, $from = null);
		
		public function escape($variable);

		public function getLastQuery();
	
		public function getError();
	
		public function getInsertedId();
	}
?>