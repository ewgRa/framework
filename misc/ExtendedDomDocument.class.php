<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * // FIXME:tested?
	*/
	class ExtendedDomDocument extends DOMDocument
	{
		const NODE_PREFIX 				= 'item';
		const NUMERIC_NODE_ATTRIBUTE 	= 'key';
		
		/**
		 * @return DomNode
		 */
		public function createNodeFromVar(
			$var,
			$nodeName,
			array $attributes = array()
		)
		{
			if(is_numeric($nodeName))
			{
				$attributes[self::NUMERIC_NODE_ATTRIBUTE] = $nodeName;
				$nodeName = self::NODE_PREFIX;
			}
			
			$node = $this->createElement($nodeName);

			foreach($attributes as $k => $v)
				$node->setAttribute($k, $v);

			if(is_array($var))
			{
				foreach($var as $k => $v)
					$node->appendChild($this->{__FUNCTION__}($v, $k));
			}
			else
				$node->appendChild($this->createCDATASection($var));
			
			return $node;
		}
		
		/**
		 * @return ExtendedDomDocument
		 */
		public function importFile($filePath)
		{
			$importNode = $this->createElementNS(
				$this->documentElement->namespaceURI,
				'xsl:import'
			);
			
			$importNode->setAttribute('href', $filePath);
			
			$this->documentElement->insertBefore(
				$importNode,
				$this->documentElement->firstChild->nextSibling
			);
			
			return $this;
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