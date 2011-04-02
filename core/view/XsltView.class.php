<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class XsltView implements ViewInterface
	{
		private $charset = 'utf8';
		private $version = '1.0';

		/**
		 * @var XsltDocument
		 */
		private $xsltDocument = null;

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
		public function loadLayout(File $layout)
		{
			Assert::isNotNull($layout->getPath());

			$this->xsltDocument = $this->createXsltDocument();
			$this->xsltDocument->loadXML($layout->getContent());

			return $this;
		}

		public function transform(Model $model)
		{
			$domModel = $this->createDomDocument();

			$root = $domModel->createNodeFromVar($model->getData(), 'document');

			$domModel->appendChild($root);

			return $this->transformXML($domModel);
		}

		public function transformXML(ExtendedDomDocument $domModel)
		{
			Assert::isNotNull($this->xsltDocument);

			$proc = new \XsltProcessor();
			$proc->importStylesheet($this->xsltDocument);

			return $proc->transformToXML($domModel);
		}

		public function toString()
		{
			return $this->xsltDocument->saveXml();
		}

		/**
		 * @return ExtendedDomDocument
		 */
		private function createDomDocument()
		{
			return
				ExtendedDomDocument::create(
					$this->getVersion(),
					$this->getCharset()
				);
		}

		/**
		 * @return XsltDocument
		 */
		private function createXsltDocument()
		{
			return
				XsltDocument::create(
					$this->getVersion(),
					$this->getCharset()
				);
		}
	}
?>