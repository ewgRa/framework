<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NullCacheTestCase extends FrameworkTestCase
	{
		protected function getRealization()
		{
			return \ewgraFramework\NullCache::create();
		}

		public function testSetAndGet()
		{
			$ticket = $this->getRealization()->createTicket();

			$this->getRealization()->set($ticket, 'a');

			$this->assertTrue($ticket->isExpired());

			$this->getRealization()->get($ticket);

			$this->assertTrue($ticket->isExpired());

			$this->getRealization()->set($ticket, 'a');

			$this->getRealization()->clean();

			$this->assertTrue($ticket->isExpired());

			$this->getRealization()->set($ticket, 'a');

			$this->getRealization()->dropByKey($this->getRealization()->compileKey($ticket));

			$this->assertTrue($ticket->isExpired());
		}
	}
?>