<?php
	/* $Id$ */

	class CacheTicketMock
	{
		public static function create()
		{
			Mock::generate('CacheTicket', 'CacheTicketTestMock');
			
			$cacheTicket = &new CacheTicketTestMock();
			
			$cacheTicket->setReturnValue('setData', $cacheTicket);
			$cacheTicket->setReturnValue('setKey', $cacheTicket);
			$cacheTicket->setReturnValue('setData', $cacheTicket);
			$cacheTicket->setReturnValue('setPrefix', $cacheTicket);
			$cacheTicket->setReturnValue('setActualTime', $cacheTicket);
			$cacheTicket->setReturnValue('restoreData', $cacheTicket);
			
			return $cacheTicket;
		}
	}
?>