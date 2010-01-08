<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class XsltViewTestCase extends FrameworkTestCase
	{
		public function testTransform()
		{
			$viewResult =
				XsltView::create()->
				loadLayout(
					File::create()->
					setPath(dirname(__FILE__).'/renderXsltView.xsl')
				)->
				transform(Model::create()->set('data', 'testData'));
			
			$this->assertSame($viewResult, 'testData');
		}
	}
?>