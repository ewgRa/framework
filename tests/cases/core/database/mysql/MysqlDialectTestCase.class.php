<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MysqlDialectTestCase extends BaseMysqlDatabaseTest
	{
		public function testGetLimit()
		{
			$this->assertEquals(
				' LIMIT 20, 10',
				$this->getInstance()->getDialect()->getLimit(10, 20)
			);

			$this->assertEquals(
				' LIMIT 10',
				$this->getInstance()->getDialect()->getLimit(10)
			);
		}

		public function testEscape()
		{
			$this->assertEquals(
				'aaa\\"aaa',
				$this->getInstance()->getDialect()->escape('aaa"aaa')
			);

			$this->assertEquals(
				array('aaa\\"aaa', 'bbb\\"bbb'),
				$this->getInstance()->getDialect()->escape(
					array('aaa"aaa', 'bbb"bbb')
				)
			);
		}

		public function testEscapeTable()
		{
			$this->assertEquals(
				'`table`',
				$this->getInstance()->getDialect()->escapeTable('table')
			);
		}
	}
?>