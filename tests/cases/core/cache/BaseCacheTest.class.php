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
			
			$cacheTicket->setData($data)->storeData();

			$clonedTicket->restoreData();
							
			$this->assertSame($data, $clonedTicket->getData());
			
			$clonedTicket = clone $cacheTicket;
			$this->realization->disable();
			$clonedTicket->restoreData();
			$this->assertTrue($clonedTicket->isExpired());
			$this->realization->enable();
			$clonedTicket = clone $cacheTicket;
			$clonedTicket->restoreData();
			$this->assertSame($data, $clonedTicket->getData());
			
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
				
			$cacheTicket->restoreData();
							
			$this->assertTrue($cacheTicket->isExpired());
		}
		
		public function testExpired()
		{
			$time = time()-1;
			$cacheTicket = $this->realization->createTicket();

			$cacheTicket->
				setLifeTime($time)->
				setPrefix($this->getPrefix())->
				setKey(rand())->
				setData($this->getData())->
				storeData()->
				restoreData();

			$this->assertTrue($cacheTicket->isExpired());
			$this->assertSame($cacheTicket->getExpiredTime(), $time);
		}
		
		public function testActualTime()
		{
			$time = time();
			
			$cacheTicket = $this->realization->createTicket();

			$cacheTicket->
				setLifeTime($time-1)->
				setPrefix($this->getPrefix())->
				setKey(rand())->
				setData($this->getData())->
				storeData()->
				setActualTime($time-2)->
				restoreData();

			$this->assertFalse($cacheTicket->isExpired());
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
			
			$cacheTicket->setData($data)->storeData()->setData(null);
			
			$cacheTicket2->setData('asdasd')->storeData()->setData(null);
			
			$cacheTicket->restoreData();
			$cacheTicket2->restoreData();
			
			$this->assertNotSame($cacheTicket->getData(), $cacheTicket2->getData());
			
			$this->assertSame($data, $cacheTicket->getData());
			$this->assertSame('asdasd', $cacheTicket2->getData());
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