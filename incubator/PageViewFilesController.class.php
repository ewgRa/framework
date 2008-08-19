<?php
	/* $Id$ */

	class PageViewFilesController extends Controller
	{
		public function importSettings($settings)
		{
			$this->setCacheKey(Page::me()->getId());
			
			return $this;
		}
		
		public function getModel()
		{
			$viewFilesId = array();
			
			foreach(ControllerDispatcher::me()->getControllers() as $controller)
			{
				$viewFilesId[] = $controller->getViewFileId();
			}
			
			return $this->getPageViewFiles($viewFilesId);
		}

		private function getPageViewFiles($filesId)
		{
			$viewFiles = array(
				'includeFiles' => array(),
				'dontSplitFiles' => array()
			);
			
			$dbQuery = '
				SELECT
					t2.id, t2.path, t2.is_can_splited, t1.recursive_include,
					t2.`content-type`
				FROM ' . Database::me()->getTable('ViewFilesIncludes') . ' t1
				INNER JOIN ' . Database::me()->getTable('ViewFiles') . ' t2
					ON(t2.id = t1.include_file_id AND t1.file_id IN(?))
				ORDER BY t1.position ASC
			';
			
			$dbResult = Database::me()->query($dbQuery, array($filesId));

			$directFiles = array();
			
			while($dbRow = Database::me()->fetchArray($dbResult))
			{
				switch($dbRow['content-type'])
				{
					case MimeContentTypes::TEXT_XSLT:
						$dbRow['path'] = str_replace(
							'\\',
							'/',
							realpath(
								Config::me()->replaceVariables($dbRow['path'])
							)
						);
					break;
				}
				
				$viewFiles['includeFiles'][] = array(
					'path' => $dbRow['path'],
					'content-type' => $dbRow['content-type'],
					'id' => $dbRow['id']
				);
				
				if($dbRow['is_can_splited'] == 'no')
				{
					$viewFiles['dontSplitFiles'][] = $dbRow['id'];
				}

				if($dbRow['recursive_include'] == 'yes')
					$directFiles[] = $dbRow['id'];
			}

			if(count($directFiles))
			{
				$includeViewFiles = $this->getPageViewFiles($directFiles);
				
				foreach($viewFiles as $k => $v)
				{
					$viewFiles[$k] = array_merge($v, $includeViewFiles[$k]);
				}
			}
		
			return $viewFiles;
		}
	}
?>