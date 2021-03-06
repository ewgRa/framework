<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PostgresqlDatabaseTestCase extends BasePostgresqlDatabaseTest
	{
		public function testBasics()
		{
			$rows = array(1 => rand(), 2 => rand());

			foreach ($rows as $key => $row) {
				$result = $this->getInstance()->insertQuery(
					\ewgraFramework\DatabaseInsertQuery::create()->
					setQuery(
						'INSERT INTO "test" (field) VALUES(?)'
					)->
					setValues(array($row))->
					setPrimaryField('id')
				);

				$this->assertEquals(
					$key,
					$result->getInsertedId()
				);
			}

			$rows[3] = rand();

			$result = $this->getInstance()->queryNull(
				\ewgraFramework\DatabaseQuery::create()->setQuery(
					'INSERT INTO "test" (id, field) VALUES(DEFAULT, '.$rows[3].')'
				)
			);

			$result = $this->getInstance()->query(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery('SELECT * FROM "test"')
			);

			$this->assertEquals(
				count($rows),
				count($result->fetchList())
			);

			$expectedList = array();

			foreach ($rows as $key => $row)
				$expectedList[] = array('id' => $key, 'field' => $row, 'created' => null);

			$this->assertEquals($expectedList, $result->fetchList());

			$this->assertEquals(array_values($rows), $result->fetchFieldList('field'));

			$this->assertEquals($rows, $result->fetchFieldList('field', 'id'));

			try {
				$this->assertEquals($rows, $result->fetchFieldList('field', 'no-id'));
				$this->fail();
			} catch (\ewgraFramework\MissingArgumentException $e) {
				# good
			}

			try {
				$this->getInstance()->queryRaw('SELECT * FROM "test2"');
				$this->fail();
			} catch (\ewgraFramework\DatabaseQueryException $e) {
				# good
			}
		}
	}
?>