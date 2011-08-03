<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class BrowserTestCase extends FrameworkTestCase
	{
		public function testIE6Detect()
		{
			$browser = \ewgraFramework\Browser::createFromUserAgent('msie 6');

			$this->assertTrue($browser->isIE6());

			$browser = \ewgraFramework\Browser::createFromUserAgent(
				'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)'
			);

			$this->assertTrue($browser->isIE6());

			$browser = \ewgraFramework\Browser::createFromUserAgent(
				'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; InfoPath.1)'
			);

			$this->assertFalse($browser->isIE6());
		}
	}
?>