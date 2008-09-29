<?php
	/* $Id$ */

	// FIXME: tested?
	abstract class BaseView implements ViewInterface
	{
		protected function getLayoutIncludeFiles($fileId)
		{
			$result = array();
			
			$dbQuery = "
				SELECT t2.path
				FROM " . Database::me()->getTable('ViewFilesIncludes') . " t1
				INNER JOIN " . Database::me()->getTable('ViewFiles') . " t2
					ON(t2.id = t1.include_file_id)
				WHERE
					t1.file_id = ? AND
					t2.`content-type` = (
						SELECT `content-type`
						FROM " . Database::me()->getTable('ViewFiles') . "
						WHERE id = ?
					)
			";
			
			$dbResult = Database::me()->query(
				$dbQuery,
				array($fileId, $fileId)
			);
			
			foreach(Database::me()->resourceToArray($dbResult) as $file)
			{
				$result[] = str_replace(
					'\\',
					'/',
					realpath(Config::me()->replaceVariables($file['path']))
				);
			}
			
			return $result;
		}
	}
?>
