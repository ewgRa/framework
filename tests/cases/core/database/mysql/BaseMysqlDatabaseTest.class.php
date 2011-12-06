<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseMysqlDatabaseTest extends BaseDatabaseTest
	{
		public function setUp()
		{
			$instance =
				\ewgraFramework\MysqlDatabase::create()->
				setHost(MYSQL_TEST_HOST)->
				setDatabase(MYSQL_TEST_DATABASE)->
				setUser(MYSQL_TEST_USER)->
				setPassword(MYSQL_TEST_PASSWORD)->
				setCharset(MYSQL_TEST_CHARSET);

			try {
				$instance->connect();
			} catch (\ewgraFramework\DatabaseConnectException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't connect to test mysql server");
				// @codeCoverageIgnoreEnd
			}

			try {
				$instance->queryRawNull(
					'DROP DATABASE IF EXISTS '.$instance->getDatabase()
				);

				$instance->queryRawNull(
					'CREATE DATABASE '.$instance->getDatabase()
				);
			} catch (\ewgraFramework\DatabaseQueryException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't clean mysql test database");
				// @codeCoverageIgnoreEnd
			}

			try {
				$instance->selectDatabase();
			} catch (\ewgraFramework\DatabaseSelectDatabaseException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't select test mysql database");
				// @codeCoverageIgnoreEnd
			}

			try {
				$instance->selectCharset();
			} catch (\ewgraFramework\DatabaseQueryException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't select test mysql charset");
				// @codeCoverageIgnoreEnd
			}

			try {
				$instance->queryRawNull(
					'CREATE TEMPORARY TABLE `test` (
  						`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
						`field` bigint(20) unsigned DEFAULT NULL,
						`created` datetime DEFAULT NULL,
					PRIMARY KEY (`id`)
					) ENGINE=MyISAM
					DEFAULT CHARSET=utf8'
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