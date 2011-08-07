<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseQueryTestCase extends FrameworkTestCase
	{
		public function testToString()
		{
			$dbQuery =
				\ewgraFramework\DatabaseQuery::create()->
				setQuery(
					'SELECT * FROM '.DummyDatabaseDialect::me()->quoteTable('a')
					.' WHERE id = ? and set_array IN (?) and set=?'
					.DummyDatabaseDialect::me()->getLimit(1, 2)
				)->
				setValues(array(1, array(2, 3)));

			$this->assertSame(
				"SELECT * FROM |a| WHERE id = '|1|' and set_array IN ('|2|', '|3|') and set=? LIMIT 2, 1",
				$dbQuery->toString(DummyDatabaseDialect::me())
			);
		}
	}
?>