<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NumberToStringTestCase extends FrameworkTestCase
	{
		public function testCommon()
		{
			$this->assertSame(
				\ewgraFramework\NumberToString::me()->toString(1010100113.225),
				'один миллиард десять миллионов сто тысяч сто тринадцать рублей двести двадцать пять копеек'
			);
		}
	}
?>