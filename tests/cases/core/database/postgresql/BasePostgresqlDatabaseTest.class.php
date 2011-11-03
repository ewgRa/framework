<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BasePostgresqlDatabaseTest extends BaseDatabaseTest
	{
		public function setUp()
		{
			$instance =
				\ewgraFramework\PostgresqlDatabase::create()->
				setHost(POSTGRESQL_TEST_HOST)->
				setDatabase(POSTGRESQL_TEST_DATABASE)->
				setUser(POSTGRESQL_TEST_USER)->
				setPassword(POSTGRESQL_TEST_PASSWORD)->
				setCharset(POSTGRESQL_TEST_CHARSET);

			try {
				$instance->connect();
			} catch (\ewgraFramework\DatabaseConnectException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't connect to test postgresql server");
				// @codeCoverageIgnoreEnd
			}

			try {
				$instance->queryRawNull(
					'DROP SCHEMA IF EXISTS test CASCADE'
				);

				$instance->queryRawNull(
					'CREATE SCHEMA test'
				);
			} catch (\ewgraFramework\DatabaseQueryException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't clean postgresql test database");
				// @codeCoverageIgnoreEnd
			}

			try {
				$instance->selectCharset();
			} catch (\ewgraFramework\DatabaseQueryException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't select test postgresql charset");
				// @codeCoverageIgnoreEnd
			}

			try {
				$instance->queryRawNull(
					'CREATE TEMP TABLE "test" (
  						"id" serial NOT NULL,
						"field" bigint NULL
					)'
				);
			} catch (\ewgraFramework\DatabaseQueryException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't create test table");
				// @codeCoverageIgnoreEnd
			}

			$this->setInstance($instance);
		}
	}
?>