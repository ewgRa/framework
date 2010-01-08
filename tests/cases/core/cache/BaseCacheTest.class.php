<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseCacheTest extends FrameworkTestCase
	{
		protected $realization = null;
		
		abstract protected function getRealization();
		
	    public function __construct($name = null, array $data = array(), $dataName = '')
		{
			parent::__construct($name, $data, $dataName);
			$this->realization = $this->getRealization();
		}
		
		public function setUp()
		{
			$this->realization->clean();
		}
		
		public function tearDown()
		{
			$this->realization->clean();
		}
		
		public function testSetAndGet()
		{
			$data = $this->getData();
			
			$cacheTicket = $this->realization->createTicket()->
				setPrefix($this->getPrefix())->
				setKey(rand());
				
			$clonedTicket = clone $cacheTicket;
			
			$cacheTicket->setData($data)->storeData();

			$clonedTicket->restoreData();
							
			$this->assertSame($data, $clonedTicket->getData());
		}

		public function testGetWithoutSet()
		{
			$data = $this->getData();
			
			$cacheTicket = $this->realization->createTicket()->
				setPrefix($this->getPrefix())->
				setKey(rand());
				
			$cacheTicket->restoreData();
							
			$this->assertTrue($cacheTicket->isExpired());
		}
		
		public function testExpired()
		{
			$cacheTicket = $this->realization->createTicket();

			$cacheTicket->
				setLifeTime(time()-1)->
				setPrefix($this->getPrefix())->
				setKey(rand())->
				setData($this->getData())->
				storeData()->
				restoreData();

			$this->assertTrue($cacheTicket->isExpired());
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