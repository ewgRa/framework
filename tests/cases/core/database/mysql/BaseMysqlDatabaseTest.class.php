<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseMysqlDatabaseTest extends FrameworkTestCase
	{
		private $instance = null;

		public function getInstance()
		{
			return $this->instance;
		}

		public function setUp()
		{
			$this->instance =
				\ewgraFramework\MysqlDatabase::create()->
				setHost(MYSQL_TEST_HOST)->
				setDatabase(MYSQL_TEST_DATABASE)->
				setUser(MYSQL_TEST_USER)->
				setPassword(MYSQL_TEST_PASSWORD)->
				setCharset(MYSQL_TEST_CHARSET);

			try {
				$this->instance->connect();
			} catch (\ewgraFramework\DatabaseConnectException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't connect to test mysql server");
				// @codeCoverageIgnoreEnd
			}

			try {
				$this->instance->queryRawNull(
					'DROP DATABASE IF EXISTS '.$this->instance->getDatabase()
				);

				$this->instance->queryRawNull(
					'CREATE DATABASE '.$this->instance->getDatabase()
				);
			} catch (\ewgraFramework\DatabaseQueryException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't clean mysql test database");
				// @codeCoverageIgnoreEnd
			}

			try {
				$this->instance->selectDatabase();
			} catch (\ewgraFramework\DatabaseSelectDatabaseException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't select test mysql database");
				// @codeCoverageIgnoreEnd
			}

			try {
				$this->instance->selectCharset();
			} catch (\ewgraFramework\DatabaseQueryException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't select test mysql charset");
				// @codeCoverageIgnoreEnd
			}

			try {
				$this->instance->queryRawNull(
					'CREATE TEMPORARY TABLE `TestTable` (
  						`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
						`field` bigint(20) unsigned DEFAULT NULL,
						PRIMARY KEY (`id`)
					) ENGINE=MyISAM
					DEFAULT CHARSET=utf8'
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

		public function testSelectDatabaseException()
		{
			$this->instance->setDatabase('nonExistsDatabase');

			try {
				$this->instance->selectDatabase();
				// @codeCoverageIgnoreStart
				$this->fail();
				// @codeCoverageIgnoreEnd
			} catch (\ewgraFramework\DatabaseSelectDatabaseException $e) {
				# good
			}
		}
	}
?>