<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class XsltDocument extends ExtendedDomDocument
	{
		/**
		 * @return XsltDocument
		 */
		public static function create($version = '1.0', $encoding = 'utf8')
		{
			return new self($version, $encoding);
		}
		
		public function load($source, $options = null)
		{
			$result = parent::load($source, $options);
			
			if (!$this->documentElement->namespaceURI) {
				Assert::isUnreachable(
					'don\'t know anything about non-xsl file'
				);
			}
			
			return $result;
		}
		
		/**
		 * @return XsltDocument
		 */
		public function importFile($filePath)
		{
			Assert::isNotNull(
				$this->documentElement,
				'are you realy want import file without root node?'
			);
			
			$importNode =
				$this->createElementNS(
					$this->documentElement->namespaceURI,
					'xsl:import'
				);

			$importNode->setAttribute('href', $filePath);
			
			$this->documentElement->
				insertBefore(
					$importNode,
					$this->documentElement->firstChild->nextSibling
				);
			
			return $this;
		}
	}
?>