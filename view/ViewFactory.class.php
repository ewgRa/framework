<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ViewFactory
	{
		/**
		 * @var ViewDA
		 */
		private static $da = null;
		
		public static function da()
		{
			if(!self::$da)
				self::$da = ViewDA::create();
				
			return self::$da;
		}
		
		/**
		 * @return BaseView
		 */
		public static function createByFileId($fileId)
		{
			$result 		= null;
			$cacheTicket 	= null;
			
			if(Cache::me()->hasTicketParams('view'))
			{
				$cacheTicket = Cache::me()->createTicket('view')->
					setKey($fileId)->
					restoreData();
			}
			
			if(!$cacheTicket || $cacheTicket->isExpired())
			{
				if($file = self::da()->getFile($fileId))
				{
					$file['path'] = Config::me()->replaceVariables($file['path']);
					
					switch($file['content-type'])
					{
						case MimeContentTypes::TEXT_XSLT:
							$result = XsltView::create()->loadLayout(
								$file['path'], $file['id']
							);
							
							$projectConfig = Config::me()->getOption('project');
							
							if(isset($projectConfig['charset']))
								$result->setCharset($projectConfig['charset']);
						break;
						case MimeContentTypes::APPLICATION_PHP:
							$result = PhpView::create()->loadLayout(
								$file['path'], $file['id']
							);
						break;
					}
				}
				else
					throw ExceptionsMapper::me()->createException('NotFound')->
						setMessage('No layout file');
				
				if($cacheTicket)
					$cacheTicket->setData($result)->storeData();
			}
			else
				$result = $cacheTicket->getData();
			
			return $result;
		}
	}
?>