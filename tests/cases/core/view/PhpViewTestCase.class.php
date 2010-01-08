<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class PhpViewTestCase extends FrameworkTestCase
	{
		public function testTransform()
		{
			$viewResult =
				PhpView::create()->
				loadLayout(
					File::create()->
					setPath(dirname(__FILE__).'/renderPhpView.php')
				)->
				transform(Model::create()->set('data', 'testData'));
			
			$this->assertSame($viewResult, 'testData');
		}
	}
?>