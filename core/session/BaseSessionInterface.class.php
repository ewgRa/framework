<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	interface BaseSessionInterface
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