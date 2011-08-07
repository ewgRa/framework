<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class RobokassaResultRequestTestCase extends FrameworkTestCase
	{
		public function testCommon()
		{
			$resultRequest =
				\ewgraFramework\RobokassaResultRequest::create(
					\ewgraFramework\HttpRequest::create()->
					setPostVar(\ewgraFramework\RobokassaRequest::ORDER_ID_KEY, 1)->
					setPostVar('OutSum', '1000,29')->
					setPostVar('SignatureValue', 'e55f7bad512ed514036a38374344b5cf')
				)->
				setPassword('baobab');

			$this->assertTrue($resultRequest->isValid());
			$this->assertSame($resultRequest->getOrderId(), 1);
			$this->assertSame($resultRequest->getTotal(), 1000.29);
		}
	}
?>