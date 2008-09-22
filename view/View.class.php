<?php
	/* $Id$ */

	// FIXME: tested?
	class View
	{
		const AJAX = 'AJAX';
		const XSLT = 'XSLT';
		
		public static function createByFileId($fileId)
		{
			$result = null;
			
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
					break;
					case MimeContentTypes::APPLICATION_PHP:
						$result = PhpView::create()->loadLayout($file);
					break;
				}
			}
						
			return $result;
		}
	}
?>