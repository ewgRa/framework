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
				setQuery('SELECT * FROM a WHERE id = ? and set=?')->
				setValues(array(1));

			$this->assertSame(
				"SELECT * FROM a WHERE id = '|1|' and set=?",
				$dbQuery->toString(DummyDatabaseDialect::me())
			);
		}
	}
?>