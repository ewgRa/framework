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
				$document->saveXml($node),
				'<nodeName attr="attrValue"><var><![CDATA[value]]></var>'
				.'<item key="1"><![CDATA[testNumber]]></item>'
				.'<item key="1_1"><![CDATA[testInvalidNodeName]]></item>'
				.'<object><var><![CDATA[1]]></var></object></nodeName>'
			);
		}

		public function testSleepAndWakeup()
		{
			$document = ExtendedDomDocument::create();
				
			$document->load(dirname(__FILE__).'/test.xsl');
			
			$this->assertEquals(
				unserialize(serialize($document)),
				$document
			);
		}
	}
?>