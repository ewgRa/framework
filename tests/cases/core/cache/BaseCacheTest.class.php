<?php
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

			$cacheTicket = $this->realization->createTicket()->
				setPrefix($this->getPrefix())->
				setKey($key1);
				
			$cacheTicket->addKey($key2);
				
			$this->assertSame(
				$cacheTicket->getKey(),
				array(array($key1), array($key2))
			);
			
			$clonedTicket = clone $cacheTicket;
			
			$cacheTicket->storeData($data);

			$clonedTicketData = $clonedTicket->restoreData();
							
			$this->assertFalse($clonedTicket->isExpired());
			$this->assertNotNull($clonedTicket->getExpiredTime());
			
			$this->assertSame($data, $clonedTicketData);
			
			$clonedTicket->drop();
			$clonedTicket = clone $cacheTicket;
			$clonedTicket->restoreData();
			$this->assertTrue($clonedTicket->isExpired());
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
		
		private function getPrefix()
		{
			return __CLASS__.__FUNCTION__.rand();
		}
		
		private function getData()
		{
			return array(rand(), rand(), rand());
		}
	}
?>