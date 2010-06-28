<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ExtendedDomDocumentTestCase extends FrameworkTestCase
	{
		public function testCreateNodeFromVar()
		{
			$document = ExtendedDomDocument::create();
			
			$node = $document->createNodeFromVar(
				array(
					'var' => 'value',
					1 => 'testNumber',
					'1_1' => 'testInvalidNodeName',
					'object' => new DomDocumentTestObject()
				),
				'nodeName',
				array('attr' => 'attrValue')
			);
				
			$this->assertSame(
				'<nodeName attr="attrValue"><var><![CDATA[value]]></var>'
				.'<item key="1"><![CDATA[testNumber]]></item>'
				.'<item key="1_1"><![CDATA[testInvalidNodeName]]></item>'
				.'<object><var><![CDATA[1]]></var></object></nodeName>',
				$document->saveXml($node)
			);
		}

		public function testGetNode()
		{
			$document = ExtendedDomDocument::create();
			
			$node = $document->createNodeFromVar(
				array(),
				'nodeName',
				array('attr' => 'attrValue')
			);
			
			$node2 = $document->createNodeFromVar(
				array(
					'var2' => 'value2',
					'var3' => 'value3'
				),
				'nodeName2'
			);
			
			$node->appendChild($node2);
			
			$document->appendChild($node);
			
			$this->assertSame(
				$document->getNode('nodeName2/var2')->nodeValue,
				'value2'
			);

			$this->assertSame(
				$document->getNodeList('nodeName2/var2|nodeName2/var3')->
				item(1)->nodeValue,
				'value3'
			);
		}
		
		public function testSleepAndWakeup()
		{
			$document = ExtendedDomDocument::create();
				
			$document->load(dirname(__FILE__).'/test.xsl');
			
			$this->assertEquals(
				$document,
				unserialize(serialize($document))
			);
		}
	}
?>