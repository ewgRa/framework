<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MysqlDatabaseTestCase extends BaseMysqlDatabaseTest
	{
		public function testBasics()
		{
			$rows = array(1 => rand(), 2 => rand());

			foreach ($rows as $key => $row) {
				$result =
					$this->getInstance()->insertQuery(
						\ewgraFramework\DatabaseInsertQuery::create()->
						setQuery(
							'INSERT INTO `TestTable` SET `field` = ?'
						)->
						setValues(array($row))
					);

				$this->assertEquals(
					$key,
					$result->getInsertedId()
				);
			}

			$rows[3] = rand();

			$result = $this->getInstance()->queryNull(
				\ewgraFramework\DatabaseInsertQuery::create()->setQuery(
					'INSERT INTO `TestTable` SET `field` = '.$rows[3]
				)
			);

			$result = $this->getInstance()->query(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery('SELECT * FROM `TestTable`')
			);

			$this->assertEquals(
				count($rows),
				count($result->fetchList())
			);

			$expectedList = array();

			foreach ($rows as $key => $row)
				$expectedList[] = array('id' => $key, 'field' => $row);

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
				$this->getInstance()->queryRaw('SELECT * FROM `TestTable2`');
				$this->fail();
			} catch (\ewgraFramework\DatabaseQueryException $e) {
				# good
			}
		}
	}
?>