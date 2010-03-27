<?php
	/* $Id$ */

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
		
		/**
		 * @return XsltDocument
		 */
		public function importFile($filePath)
		{
			Assert::isNotNull(
				$this->documentElement,
				'are you realy want import file without root node?'
			);
			
			$importNode = null;
			
			if ($this->documentElement->namespaceURI) {
				$importNode =
					$this->createElementNS(
						$this->documentElement->namespaceURI,
						'xsl:import'
					);
			} else {
				Assert::isUnreachable(
					'don\'t know how import file in non-xsl document'
				);
			}

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