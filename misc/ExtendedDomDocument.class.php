<?php
	// FIXME:tested?
	class ExtendedDomDocument extends DOMDocument
	{
		const DEFAULT_NODE_PREFIX = 'item';
		const DEFAULT_NUMERIC_NODE_ATTRIBUTE = 'key';
		
		public function toString()
		{
			return $this->saveXML();
		}
		
		public function createNodeFromArray($array, $nodeName, $attributes = array())
		{
			if(is_numeric($nodeName))
			{
				$attributes[self::DEFAULT_NUMERIC_NODE_ATTRIBUTE] = $nodeName;
				$nodeName = self::DEFAULT_NODE_PREFIX;
			}
			
			$node = $this->createElement($nodeName);

			foreach($attributes as $k => $v )
			{
				$node->setAttribute($k, $v);
			}

			if(!is_array($array))
			{
				$CData = $this->createCDATASection($array);
				$node->appendChild($CData);
			}
			else
			{
				foreach($array as $k => $v)
					$node->appendChild($this->createNodeFromArray($v, $k));
			}

			return $node;
		}
		
		public function __sleep()
		{
			$this->xml = $this->toString();
			
			return array('xml');
		}
		
		public function __wakeup()
		{
			$this->loadXML($this->xml);
			unset($this->xml);
		}
	}
?>