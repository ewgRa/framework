<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DefaultDatabaseCacheWorkerTestCase extends FrameworkTestCase
	{
		private $savedDatabase = null;
		private $savedCache = null;

		public function setUp()
		{
			$this->savedDatabase = serialize(\ewgraFramework\Database::me());
			\ewgraFramework\TestSingleton::dropInstance('ewgraFramework\Database');
			$this->savedCache = serialize(\ewgraFramework\Cache::me());
			\ewgraFramework\TestSingleton::dropInstance('ewgraFramework\Cache');
		}

		public function tearDown()
		{
			\ewgraFramework\TestSingleton::setInstance(
				'ewgraFramework\Database',
				unserialize($this->savedDatabase)
			);

			\ewgraFramework\TestSingleton::setInstance(
				'ewgraFramework\Cache',
				unserialize($this->savedCache)
			);
		}

		public function testCommon()
		{
			$cacheInstance =
				\ewgraFramework\MemcachedBasedCache::create()->
				addServer(MEMCACHED_TEST_HOST, MEMCACHED_TEST_PORT);

			$cacheInstance->clean();

			\ewgraFramework\Cache::me()->addPool($cacheInstance, 'testPool');

			$dbInstance =
				\ewgraFramework\PostgresqlDatabase::create()->
				setHost(POSTGRESQL_TEST_HOST)->
				setDatabase(POSTGRESQL_TEST_DATABASE)->
				setUser(POSTGRESQL_TEST_USER)->
				setPassword(POSTGRESQL_TEST_PASSWORD)->
				setCharset(POSTGRESQL_TEST_CHARSET);

			try {
				$dbInstance->connect();
			} catch (\ewgraFramework\DatabaseConnectException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't connect to test postgresql server");
				// @codeCoverageIgnoreEnd
			}

			try {
				$dbInstance->queryRawNull(
					'DROP SCHEMA IF EXISTS test CASCADE'
				);

				$dbInstance->queryRawNull(
					'CREATE SCHEMA test'
				);
			} catch (\ewgraFramework\DatabaseQueryException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't clean postgresql test database");
				// @codeCoverageIgnoreEnd
			}

			try {
				$dbInstance->selectCharset();
			} catch (\ewgraFramework\DatabaseQueryException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't select test postgresql charset");
				// @codeCoverageIgnoreEnd
			}

			try {
				$dbInstance->queryRawNull('SET search_path TO public,test');

				$dbInstance->queryRawNull(
					'CREATE TABLE "test"."test" (
  						"id" bigint NOT NULL,
						"field" bigint NULL
					)'
				);
			} catch (\ewgraFramework\DatabaseQueryException $e) {
				// @codeCoverageIgnoreStart
				$this->markTestSkipped("can't create test table");
				// @codeCoverageIgnoreEnd
			}

			\ewgraFramework\Database::me()->addPool($dbInstance, 'testPool');

			$dbInstance->queryNull(
				\ewgraFramework\DatabaseQuery::create()->setQuery(
					'INSERT INTO "test" (id, field) VALUES(1, 1)'
				)
			);

			$request =
				\ewgraFramework\DatabaseCacheRequest::create(
					\ewgraFramework\DatabaseQuery::create()->
					setQuery('SELECT * FROM "test" WHERE field=1'),
					'testPool',
					'testPool'
				);

			$worker = \ewgraFramework\DefaultDatabaseCacheWorker::me();

			$result = $worker->getCached($request, array('tag1', 'tag2'));

			$this->assertSame($result, array('id' => '1', 'field' => '1'));

			$dbInstance->queryNull(
				\ewgraFramework\DatabaseQuery::create()->setQuery(
					'DELETE FROM "test" WHERE id=1'
				)
			);

			$result = $worker->getCached($request, array('tag1', 'tag2'));

			$this->assertSame($result, array('id' => '1', 'field' => '1'));

			$dbInstance->queryNull(
				\ewgraFramework\DatabaseQuery::create()->setQuery(
					'INSERT INTO "test" (id, field) VALUES(2, 1)'
				)
			);

			$worker->dropCache($request, array('tag2'));

			$result = $worker->getCached($request, array('tag1', 'tag2'));

			$this->assertSame($result, array('id' => '2', 'field' => '1'));

			$cacheInstance->clean();
		}
	}
?>