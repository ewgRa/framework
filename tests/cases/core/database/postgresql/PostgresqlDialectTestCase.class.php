<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PostgresqlDialectTestCase extends BasePostgresqlDatabaseTest
	{
		public function testGetLimit()
		{
			$this->assertEquals(
				' LIMIT 10 OFFSET 20',
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
				"aaa''aaa",
				$this->getInstance()->getDialect()->escape("aaa'aaa")
			);

			$this->assertEquals(
				array("aaa''aaa", "bbb''bbb"),
				$this->getInstance()->getDialect()->escape(
					array("aaa'aaa", "bbb'bbb")
				)
			);
		}

		public function testEscapeTable()
		{
			$this->assertEquals(
				'"table"',
				$this->getInstance()->getDialect()->escapeTable('table')
			);
		}

		public function testCondition()
		{
			$this->assertEquals(
				'CASE WHEN 1 THEN 2 ELSE 3 END',
				$this->getInstance()->getDialect()->condition(1, 2, 3)
			);
		}

		public function testCreateOrder()
		{
			$this->assertTrue(
				$this->getInstance()->getDialect()->createOrder('name')
				instanceof \ewgraFramework\DatabaseQueryOrderInterface
			);
		}

		public function testOrderString()
		{
			$order = $this->getInstance()->getDialect()->createOrder('name');

			$order->desc()->nullsLast();

			$this->assertSame(
				'"name" DESC NULLS LAST',
				$this->getInstance()->getDialect()->getOrderString($order)
			);

			$order->desc()->nullsFirst();

			$this->assertSame(
				'"name" DESC NULLS FIRST',
				$this->getInstance()->getDialect()->getOrderString($order)
			);
		}
	}
?>