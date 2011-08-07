<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class RobokassaRequestTestCase extends FrameworkTestCase
	{
		public function testCompileUrl()
		{
			$request = \ewgraFramework\RobokassaRequest::create();

			$request->
				productionMode()->
				testMode()->
				setCurrency(
					\ewgraFramework\RobokassaCurrency::create(
						\ewgraFramework\RobokassaCurrency::BANK_CARD
					)
				)->
				setLogin('baobab')->
				setPassword('baobabPass')->
				setTotal('1000,29')->
				setOrderId(1)->
				setDescription('description')->
				setUserEmail('userEmail');

			$this->assertSame(
				$request->getUrl()->__toString(),
				\ewgraFramework\HttpUrl::createFromString(
					'http://test.robokassa.ru/Index.aspx?MrchLogin=baobab'
					.'&OutSum=1000.29&InvId=1&Desc=description'
					.'&SignatureValue=f9b5d4f4b2b1205883306c63901273c4'
					.'&Email=userEmail&Culture=ru&IncCurrLabel=BANKOCEAN2R'
				)->__toString()
			);
		}
	}
?>