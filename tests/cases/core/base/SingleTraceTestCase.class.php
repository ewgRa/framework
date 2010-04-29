<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SingleTraceTestCase extends FrameworkTestCase
	{
		public function testCommon()
		{
			$file = __FILE__;
			$line = __LINE__;
			
			$this->assertSame(
				(string)SingleTrace::create()->setFile($file)->setLine($line),
				$file.'@'.$line
			);
		}
	}
?>