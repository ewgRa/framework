<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class XsltDocumentTestCase extends FrameworkTestCase
	{
		public function testImportFile()
		{
			$document = XsltDocument::create();

			$document->load(dirname(__FILE__).'/test.xsl');

			$this->assertEquals(
				$document->getDocumentElement()->nodeName,
				'xsl:stylesheet'
			);
			
			$document->importFile('testFile');
			
			$this->assertTrue(
				strpos(
					$document->saveXml(),
					'<xsl:import href="testFile"/>'
				) > 0
			);
		}

		public function testLoadNonXslDocument()
		{
			$document = XsltDocument::create();
	
			try {
				$document->load(dirname(__FILE__).'/test.xml');
				$this->fail();
			} catch (UnreachableCodeReachedException $e) {
				# all good
			}
		}
	}
?>