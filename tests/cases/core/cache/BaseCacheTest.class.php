<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseCacheTest extends FrameworkTestCase
	{
		protected $realization = null;

		abstract protected function getRealization();

		public function setUp()
		{
			$this->realization = $this->getRealization();
		}

		public function tearDown()
		{
			$this->realization->clean();
		}

		public function testSetAndGet()
		{
			$data = $this->getData();

			$key1 = rand();
			$key2 = rand();

			$cacheTicket =
				$this->realization->createTicket()->
				setPrefix($this->getPrefix())->
				setKey($key1);

			$cacheTicket->addKey($key2);

			$this->assertSame(
				array(array($key1), array($key2)),
				$cacheTicket->getKey()
			);

			$clonedTicket = clone $cacheTicket;

			$cacheTicket->storeData($data);

			$clonedTicketData = $clonedTicket->restoreData();

			$this->assertFalse($clonedTicket->isExpired());
			$this->assertNotNull($clonedTicket->getExpiredTime());

			$this->assertSame($data, $clonedTicketData);

			$clonedTicket->drop();
			$this->assertTrue($clonedTicket->isExpired());
			$clonedTicket = clone $cacheTicket;
			$clonedTicket->restoreData();
			$this->assertTrue($clonedTicket->isExpired());
		}

		public function testMultiSetAndGet()
		{
			$data = $this->getData();
			$data2 = $this->getData();

			$key1 = rand();
			$key2 = rand();

			$cacheTicket =
				$this->realization->createTicket()->
				setPrefix($this->getPrefix())->
				setKey($key1);

			$cacheTicket2 =
				$this->realization->createTicket()->
				setPrefix($this->getPrefix())->
				setKey($key2);

			$clonedTicket = clone $cacheTicket;
			$clonedTicket2 = clone $cacheTicket2;

			$settedData =
				array(
					$key1 => $data,
					$key2 => $data2
				);

			$this->realization->multiSet(
				array(
					$key1 => $cacheTicket,
					$key2 => $cacheTicket2
				),
				$settedData
			);

			$getData = $this->realization->multiGet(
				array(
					$key1 => $clonedTicket,
					$key2 => $clonedTicket2
				)
			);

			$this->assertFalse($clonedTicket->isExpired());
			$this->assertFalse($clonedTicket2->isExpired());
			$this->assertNotNull($clonedTicket->getExpiredTime());
			$this->assertNotNull($clonedTicket2->getExpiredTime());

			$this->assertSame(
				$settedData,
				$getData
			);

			$clonedTicket->drop();
			$clonedTicket = clone $cacheTicket;

			$getData = $this->realization->multiGet(
				array(
					$key1 => $clonedTicket,
					$key2 => $clonedTicket2
				)
			);

			$this->assertTrue($clonedTicket->isExpired());

			$this->assertSame(
				array($key2 => $data2),
				$getData
			);
		}

		public function testGetWithoutSet()
		{
			$data = $this->getData();

			$cacheTicket =
				$this->realization->createTicket()->
				setPrefix($this->getPrefix())->
				setKey(rand());

			$cacheTicket->actual();

			$cacheTicket->restoreData();

			$this->assertTrue($cacheTicket->isExpired());
		}

		public function testExpired()
		{
			$cacheTicket = $this->realization->createTicket();

			$cacheTicket->
				setLifeTime(1)->
				setPrefix($this->getPrefix())->
				setKey(rand())->
				storeData($this->getData());

			$cacheTicket->restoreData();

			$this->assertFalse($cacheTicket->isExpired());
			$this->assertNotNull($cacheTicket->getExpiredTime());

			sleep(2);

			$cacheTicket->restoreData();

			$this->assertTrue($cacheTicket->isExpired());
			$this->assertNull($cacheTicket->getExpiredTime());
		}

		public function testNamespace()
		{
			$data = $this->getData();

			$cacheTicket =
				$this->realization->createTicket()->
					setPrefix($this->getPrefix())->
					setKey(rand());

			$cacheTicket2 = clone $cacheTicket;
			$cacheTicket2->setCacheInstance($this->getRealization());

			$cacheTicket->getCacheInstance()->setNamespace('a');
			$cacheTicket2->getCacheInstance()->setNamespace('b');

			$cacheTicket->storeData($data);

			$cacheTicket2->storeData('asdasd');

			$data1 = $cacheTicket->restoreData();
			$data2 = $cacheTicket2->restoreData();

			$this->assertNotSame($data1, $data2);

			$this->assertSame($data, $data1);
			$this->assertSame('asdasd', $data2);
		}

		public function testDiffKey()
		{
			$cacheTicket =
				$this->realization->createTicket()->
				setPrefix($this->getPrefix())->
				setKey(array(rand(), rand()));

			$cacheTicket2 =
				$this->realization->createTicket()->
				setPrefix($cacheTicket->getPrefix())->
				setKey(array(rand(), rand()));

			$this->assertNotSame(
				$this->realization->compileKey($cacheTicket),
				$this->realization->compileKey($cacheTicket2)
			);
		}

		protected function getPrefix()
		{
			return __CLASS__.'_'.__FUNCTION__.'_'.rand();
		}

		protected function getData()
		{
			return array(rand(), rand(), rand());
		}
	}
?>