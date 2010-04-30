<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface SessionInterface
	{
		public static function create();
		
		public function relativeStart();

		public function start();
		
		public function save();
		
		public function getId();
		
		public function has($key);
		
		public function get($key);
		
		public function set($key, $value);
		
		public function drop($key);
	}
?>