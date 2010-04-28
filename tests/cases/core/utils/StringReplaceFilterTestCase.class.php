<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class StringReplaceFilterTestCase extends FrameworkTestCase
	{
		public function testApply()
		{
			$this->assertSame(
				 StringReplaceFilter::create()->
				 addReplacement('a', 'c')->
				 addReplacement('b', 'c')->
				 apply('ab'),
				 'cc'
			);
		}
	}
?>