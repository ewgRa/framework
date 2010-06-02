<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface CacheInterface
	{
		public static function create();
		
		public function createTicket();
		
		public function get(CacheTicket $ticket);
		
		public function set(CacheTicket $ticket, $data);
		
		public function drop(CacheTicket $cacheTicket);
		
		public function dropByKey($key);
		
		public function compileKey(CacheTicket $ticket);
		
		public function clean();
	}
?>