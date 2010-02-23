<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class TimestampTestCase extends FrameworkTestCase
	{
		public function testCreateFromString()
		{
			$date = '2009-04-12 13:12:16';
			
			$timestamp = Timestamp::createFromString($date);
			$this->assertSame($timestamp->__toString(), $date);
		}

		public function testCreateNow()
		{
			$timestamp = Timestamp::createNow();
			
			$this->assertSame($timestamp->getTime(), time());
		}
	}
?>