<?php
	/* $Id$ */

	class CacheTicketMock
	{
		public static function create()
		{
			Mock::generate('CacheTicket', 'CacheTicketTestMock');
			
			$cacheTicket = &new CacheTicketTestMock();
			
			return $cacheTicket;
		}
	}
?>