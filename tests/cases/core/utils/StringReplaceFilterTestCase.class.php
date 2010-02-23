<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class StringReplaceFilterTestCase extends FrameworkTestCase
	{
		public function testApply()
		{
			$this->assertSame(
				 StringReplaceFilter::create()->
				 setSearch(array('a','b'))->
				 setReplace(array('c','c'))->
				 apply('ab'),
				 'cc'
			);
		}
	}
?>