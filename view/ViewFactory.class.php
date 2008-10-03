<?php
	/* $Id$ */

	// FIXME: tested?


	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	final class ViewFactory
	{
		/**
		 * @return BaseView
		 */
		public static function createByFileId($fileId)
		{
			$result = null;
			$cacheTicket = null;
			
			if(Cache::me()->hasTicketParams('view'))
			{
				$cacheTicket = Cache::me()->createTicket('view')->
					setKey($fileId)->
					restoreData();
			}
			
			if(!$cacheTicket || $cacheTicket->isExpired())
			{
				$dbQuery = "SELECT * FROM " . Database::me()->getTable('ViewFiles')
					. ' WHERE id = ?';
				
				$dbResult = Database::me()->query($dbQuery, array($fileId));
	
				if(Database::me()->recordCount($dbResult))
				{
					$file = Database::me()->fetchArray($dbResult);
					$file['path'] = Config::me()->replaceVariables($file['path']);
					
					switch($file['content-type'])
					{
						case MimeContentTypes::TEXT_XSLT:
							$result = XsltView::create()->loadLayout($file);
							$projectConfig = Config::me()->getOption('project');
							
							if(isset($projectConfig['charset']))
								$result->setCharset($projectConfig['charset']);
						break;
						case MimeContentTypes::APPLICATION_PHP:
							$result = PhpView::create()->loadLayout($file);
						break;
					}
				}
				
				if($cacheTicket)
					$cacheTicket->setData($result)->storeData();
			}
			else
				$result = $cacheTicket->getData();
			
			return $result;
		}
	}
?>