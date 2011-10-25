<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DateTimeRangeTestCase extends FrameworkTestCase
	{
		public function testCommon()
		{
			$dateStart = \ewgraFramework\DateTime::makeNow();
			$dateEnd = \ewgraFramework\DateTime::makeNow()->modify('+1 year');

			$dateRange =
				\ewgraframework\DateTimeRange::create()->
				setStart($dateStart)->
				setEnd($dateEnd);

			$this->assertSame($dateStart, $dateRange->getStart());

			$this->assertSame($dateEnd, $dateRange->getEnd());

			$this->assertFalse($dateRange->isEqMonth());

			$dateStart = \ewgraFramework\DateTime::create('2010-01-01');
			$dateEnd = \ewgraFramework\DateTime::create('2010-01-02');

			$dateRange =
				\ewgraframework\DateTimeRange::create()->
				setStart($dateStart)->
				setEnd($dateEnd);

			$this->assertTrue($dateRange->isOneMonth());
		}
	}
?>