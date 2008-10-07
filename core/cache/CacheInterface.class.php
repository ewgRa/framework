<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	interface CacheInterface
	{
		public static function create();
		
		public function get(CacheTicket $ticket);
		
		public function set(CacheTicket $ticket);
		
		public function createTicket();

		public function disable();
		
		public function enable();

		public function isDisabled();
		
		public function hasTicketParams($ticketAlias);
		
		public function getTicketParams($ticketAlias);
		
		public function loadConfig($yamlFile);
	}
?>