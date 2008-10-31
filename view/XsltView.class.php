<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * // FIXME: tested?
	*/
	class XsltView extends BaseView
	{
		private $charset	 = 'utf8';
		private $version	 = '1.0';
		
		/**
		 * @var ExtendedDomDocument
		 */
		private $xslDocument = null;

		/**
		 * @return XsltView
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return XsltView
		 */
		public function setCharset($charset)
		{
			$this->charset = $charset;
			return $this;
		}
		
		public function getCharset()
		{
			return $this->charset;
		}
		
		/**
		 * @return XsltView
		 */
		public function setVersion($version)
		{
			$this->version = $version;
			return $this;
		}
		
		public function getVersion()
		{
			return $this->version;
		}
		
		/**
		 * @return XsltView
		 */
		public function createLayout($filePath)
		{
			$this->xslDocument = $this->createDomDocument();
			
			$this->xslDocument->loadXML(file_get_contents($filePath));
			
			return $this;
		}
		
		/**
		 * @return XsltView
		 */
		public function loadLayout($file)
		{
			$this->createLayout($file['path']);
			
			foreach($this->getLayoutIncludeFiles($file['id']) as $includeFile)
				$this->xslDocument->importFile($includeFile['path']);
			
			return $this;
		}
		
		public function transform(Model $model)
		{
			$domModel = $this->createDomDocument();

			$root = $domModel->createNodeFromVar($model->getData(), 'document');

			$domModel->appendChild($root);
		
			$proc = new XsltProcessor();
			$proc->importStylesheet($this->xslDocument);

			return $proc->transformToXML($domModel);
		}
		
		public function toString()
		{
			return $this->xslDocument->saveXml();
		}
		
		/**
		 * @return ExtendedDomDocument
		 */
		private function createDomDocument()
		{
			return new ExtendedDomDocument(
				$this->getVersion(),
				$this->getCharset()
			);
		}
	}
?>
