<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BasePostgresqlDatabaseTest extends FrameworkTestCase
	{
		private $instance = null;

		public function getInstance()
		{
			return $this->instance;
		}

		public function setUp()
		{
			$this->instance =
				\ewgraFramework\PostgresqlDatabase::create()->
				setHost(POSTGRESQL_TEST_HOST)->
				setDatabase(POSTGRESQL_TEST_DATABASE)->
				setUser(POSTGRESQL_TEST_USER)->
				setPassword(POSTGRESQL_TEST_PASSWORD)->
				setCharset(POSTGRESQL_TEST_CHARSET);

			try {
				$this->instance->connect();
			} catch (\ewgraFramework\DatabaseConnectException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't connect to test postgresql server");
				// @codeCoverageIgnoreEnd
			}

			try {
				$this->instance->queryRawNull(
					'DROP SCHEMA IF EXISTS test CASCADE'
				);

				$this->instance->queryRawNull(
					'CREATE SCHEMA test'
				);
			} catch (\ewgraFramework\DatabaseQueryException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't clean postgresql test database");
				// @codeCoverageIgnoreEnd
			}

			try {
				$this->instance->selectCharset();
			} catch (\ewgraFramework\DatabaseQueryException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't select test postgresql charset");
				// @codeCoverageIgnoreEnd
			}

			try {
				$this->instance->queryRawNull(
					'CREATE TEMP TABLE "TestTable" (
  						"id" serial NOT NULL,
						"field" bigint NULL
					)'
				);
			} catch (\ewgraFramework\DatabaseQueryException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't create test table");
				// @codeCoverageIgnoreEnd
			}
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
	}
?>