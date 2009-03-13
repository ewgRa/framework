<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
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