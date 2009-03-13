<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class XsltView extends BaseView
	{
		private $charset = 'utf8';
		private $version = '1.0';
		
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
		public function loadLayout($filePath, $fileId = null)
		{
			$this->createLayout($filePath);
			
			if($fileId)
			{
				foreach($this->getLayoutIncludeFiles($fileId) as $includeFile)
					$this->xslDocument->importFile($includeFile['path']);
			}
			
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

		/**
		 * @return XsltView
		 */
		private function createLayout($filePath)
		{
			$this->xslDocument = $this->createDomDocument();
			
			$this->xslDocument->loadXML(file_get_contents($filePath));
			
			return $this;
		}
	}
?>