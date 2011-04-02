<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class AssertTestCase extends FrameworkTestCase
	{
		public function testIsArray()
		{
			$this->assertTrue(\ewgraFramework\Assert::isArray(array(rand())));

			try {
				\ewgraFramework\Assert::isArray(rand());
				$this->fail();
			} catch(\ewgraFramework\WrongArgumentException $e) {
				# all good
			}
		}

		public function testIsNotEmpty()
		{
			$this->assertTrue(\ewgraFramework\Assert::isNotEmpty(array(1)));

			try {
				\ewgraFramework\Assert::isNotEmpty(array());
				$this->fail();
			} catch(\ewgraFramework\WrongArgumentException $e) {
				# all good
			}
		}

		public function testIsTrue()
		{
			$this->assertTrue(\ewgraFramework\Assert::isTrue(true));

			try {
				\ewgraFramework\Assert::isTrue(false);
				$this->fail();
			} catch(\ewgraFramework\WrongArgumentException $e) {
				# all good
			}
		}

		public function testIsFalse()
		{
			$this->assertTrue(\ewgraFramework\Assert::isFalse(false));

			try {
				\ewgraFramework\Assert::isFalse(true);
				$this->fail();
			} catch(\ewgraFramework\WrongArgumentException $e) {
				# all good
			}
		}

		public function testIsNotNull()
		{
			$this->assertTrue(\ewgraFramework\Assert::isNotNull(1));

			try {
				\ewgraFramework\Assert::isNotNull(null);
				$this->fail();
			} catch(\ewgraFramework\WrongArgumentException $e) {
				# all good
			}
		}

		public function testIsEqual()
		{
			$this->assertTrue(\ewgraFramework\Assert::isEqual(1, 1));

			try {
				\ewgraFramework\Assert::isEqual(1, '1');
				$this->fail();
			} catch(\ewgraFramework\WrongArgumentException $e) {
				# all good
			}
		}

		public function testIsFileExists()
		{
			$this->assertTrue(\ewgraFramework\Assert::isFileExists(__FILE__));

			try {
				\ewgraFramework\Assert::isFileExists('noFile');
				$this->fail();
			} catch(\ewgraFramework\FileNotExistsException $e) {
				# all good
			}
		}

		public function testIsImplement()
		{
			$this->assertTrue(
				\ewgraFramework\Assert::isImplement(
					\ewgraFramework\Cache::me(),
					'ewgraFramework\SingletonInterface'
				)
			);

			try {
				\ewgraFramework\Assert::isImplement(
					$this,
					'ewgraFramework\SingletonInterface'
				);
				$this->fail();
			} catch(\ewgraFramework\WrongArgumentException $e) {
				# all good
			}
		}

		public function testIsUnreachable()
		{
			try {
				\ewgraFramework\Assert::isUnreachable();
				$this->fail();
			} catch(\ewgraFramework\UnreachableCodeReachedException $e) {
				# all good
			}
		}
	}
?>