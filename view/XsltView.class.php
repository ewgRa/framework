<?php
	/* $Id$ */

	// FIXME: refactoring
	// FIXME: tested?
	class XsltView
	{
		private $xslDocument = null;
		private $meta = null;
		
		public static function create()
		{
			return new self;
		}
		
		public function loadLayout($file)
		{
			$projectOptions = Config::me()->getOption('project');
			$this->xslDocument = new DomDocument('1.0', $projectOptions['charset']);

			$this->xslDocument->loadXML(
				file_get_contents($file['path'])
			);
			
			$dbQuery = "
				SELECT t2.path
				FROM " . Database::me()->getTable('ViewFilesIncludes') . " t1
				INNER JOIN " . Database::me()->getTable('ViewFiles') . " t2
					ON(t2.id = t1.include_file_id)
				WHERE
					t1.file_id = ? AND
					t2.`content-type` = 'text/xslt'
			";
			
			$dbResult = Database::me()->query(
				$dbQuery,
				array($file['id'])
			);
			
			$files = Database::me()->resourceToArray($dbResult);

			foreach($files as $file)
			{
				$file['path'] = str_replace(
					'\\',
					'/',
					realpath(
						Config::me()->replaceVariables($file['path'])
					)
				);
				
				$importNode = $this->xslDocument->createElementNS(
					$this->xslDocument->documentElement->namespaceURI,
					'xsl:import'
				);
				
				$importNode->setAttribute('href', $file['path']);
				
				$this->xslDocument->documentElement->insertBefore(
					$importNode,
					$this->xslDocument->documentElement->firstChild->nextSibling
				);
			}
			
			return $this;
		}
		
		public function transform(DomDocument $model)
		{
			$proc = new XsltProcessor();
			$proc->importStylesheet($this->xslDocument);

			return $proc->transformToXML($model);
		}
		
		function toString()
		{
			return $this->xslDocument->saveXml();
		}
	}
?>
