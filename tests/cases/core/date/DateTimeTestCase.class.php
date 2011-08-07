<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DateTimeTestCase extends FrameworkTestCase
	{
		public function testCommon()
		{
			$date = \ewgraFramework\DateTime::makeNow();

			$time = time();

			$this->assertLessThan(1, $time - $date->getTimestamp());

			$date = \ewgraFramework\DateTime::createFromTimestamp($time);

			$this->assertSame($time, $date->getTimestamp());

			$date = \ewgraFramework\DateTime::create('1984-04-12');

			$this->assertSame(1984, $date->getYear());
			$this->assertSame(4, $date->getMonth());
			$this->assertSame(12, $date->getDay());
		}
	}
?>