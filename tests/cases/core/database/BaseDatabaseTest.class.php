<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	use ewgraFramework\DatabaseInterface;

	abstract class BaseDatabaseTest extends FrameworkTestCase
	{
		private $instance = null;

		public function getInstance()
		{
			return $this->instance;
		}

		public function setInstance(DatabaseInterface $instance)
		{
			$this->instance = $instance;
			return $this;
		}

		public function tearDown()
		{
			$this->instance = null;
		}

		public function testConnectException()
		{
			$this->instance->disconnect();
			$this->instance->setHost('nonExistsHost');

			try {
				$this->instance->connect();
				// @codeCoverageIgnoreStart
				$this->fail();
				// @codeCoverageIgnoreEnd
			} catch (\ewgraFramework\DatabaseConnectException $e) {
				# good
			}
		}

		public function testDisconnect()
		{
			$this->instance->disconnect();
			$this->assertNull($this->instance->getLinkIdentifier());
		}

		public function testDatabaseNotExistsException()
		{
			$this->instance->setDatabase('nonExistsDatabase');

			try {
				$this->instance->disconnect()->connect();
				// @codeCoverageIgnoreStart
				$this->fail();
				// @codeCoverageIgnoreEnd
			} catch (\ewgraFramework\DatabaseConnectException $e) {
				# good
			}
		}

		public function testSerialize()
		{
			try {
				serialize($this->getInstance());
				$this->fail();
			} catch (\ewgraFramework\UnsupportedException $e) {
				# good
			}
		}
	}
?>