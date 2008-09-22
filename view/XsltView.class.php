<?php
	/* $Id$ */

	// FIXME: tested?
	class XsltView extends BaseView
	{
		private $xslDocument = null;

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
			
			foreach($this->getLayoutIncludeFiles($file['id']) as $includeFile)
			{
				$importNode = $this->xslDocument->createElementNS(
					$this->xslDocument->documentElement->namespaceURI,
					'xsl:import'
				);
				
				$importNode->setAttribute('href', $includeFile['path']);
				
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
		
		public function toString()
		{
			return $this->xslDocument->saveXml();
		}
	}
?>
