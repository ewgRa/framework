<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface CacheInterface
	{
		public static function create();
		
		public function get(CacheTicket $ticket);
		
		public function set(CacheTicket $ticket);
		
		public function disable();
		
		public function enable();

		public function isDisabled();
	}
?>