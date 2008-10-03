<?php
	/* $Id$ */

	// FIXME:tested?


	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	class ExtendedDomDocument extends DOMDocument
	{
		const DEFAULT_NODE_PREFIX = 'item';
		const DEFAULT_NUMERIC_NODE_ATTRIBUTE = 'key';
		
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
				$attributes[self::DEFAULT_NUMERIC_NODE_ATTRIBUTE] = $nodeName;
				$nodeName = self::DEFAULT_NODE_PREFIX;
			}
			
			$node = $this->createElement($nodeName);

			foreach($attributes as $k => $v )
				$node->setAttribute($k, $v);

			if(!is_array($var))
			{
				$CData = $this->createCDATASection($var);
				$node->appendChild($CData);
			}
			else
			{
				foreach($var as $k => $v)
					$node->appendChild($this->{__FUNCTION__}($v, $k));
			}

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