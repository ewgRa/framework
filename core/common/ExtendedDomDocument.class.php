<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class ExtendedDomDocument extends DOMDocument
	{
		const NODE_PREFIX 				= 'item';
		const NUMERIC_NODE_ATTRIBUTE 	= 'key';
		
		/**
		 * @return ExtendedDomDocument
		 */
		public static function create($version = '1.0', $encoding = 'utf8')
		{
			return new self($version, $encoding);
		}
		
		/**
		 * @return DomNode
		 */
		public function createNodeFromVar(
			$var,
			$nodeName,
			array $attributes = array()
		) {
			try {
				$node = $this->createElement($nodeName);
			} catch (DOMException $e) {
				$attributes[self::NUMERIC_NODE_ATTRIBUTE] = $nodeName;
				$nodeName = self::NODE_PREFIX;
				$node = $this->createElement($nodeName);
			}

			foreach ($attributes as $k => $v)
				$node->setAttribute($k, $v);

			if (is_object($var))
				$var = (array)$var;
			
			if (is_array($var)) {
				foreach ($var as $k => $v)
					$node->appendChild($this->{__FUNCTION__}($v, $k));
				
			} else {
				$node->appendChild($this->createCDATASection($var));
			}
			
			return $node;
		}
		
		/**
		 * @return DOMElement
		 */
		public function getDocumentElement()
		{
			return $this->documentElement;
		}
		
		public function toString()
		{
			return $this->saveXML();
		}
		
		public function __sleep()
		{
			$this->xml = $this->toString();
			
			return array('xml');
		}
		
		/**
		 * @return ExtendedDomDocument
		 */
		public function __wakeup()
		{
			$this->loadXML($this->xml);
			unset($this->xml);
			
			return $this;
		}
	}
?>