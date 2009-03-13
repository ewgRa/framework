<?php
	/* $Id$ */

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
		
		public function has($alias);
		
		public function get($alias);
		
		public function set($alias, $value);
		
		public function drop($alias);
	}
?>