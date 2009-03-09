<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	abstract class CacheWorker
	{
		abstract protected function getAlias();
		abstract protected function getKey();
		
		/**
		 * @return CacheTicket
		 */
		public function createTicket()
		{
			$result = null;
			
			if(Cache::me()->hasTicketParams($this->getAlias()))
			{
				$result =
					Cache::me()->createTicket($this->getAlias())->
						setKey($this->getKey());
			}
			
			return $result;
		}
		
	}
?>