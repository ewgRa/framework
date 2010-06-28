<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class AssertTestCase extends FrameworkTestCase
	{
		public function testIsArray()
		{
			$this->assertTrue(Assert::isArray(array(rand())));
			
			try {
				Assert::isArray(rand());
				$this->fail();
			} catch(WrongArgumentException $e) {
				# all good
			}
		}

		public function testIsNotEmpty()
		{
			$this->assertTrue(Assert::isNotEmpty(array(1)));
			
			try {
				Assert::isNotEmpty(array());
				$this->fail();
			} catch(WrongArgumentException $e) {
				# all good
			}
		}
		
		public function testIsTrue()
		{
			$this->assertTrue(Assert::isTrue(true));
			
			try {
				Assert::isTrue(false);
				$this->fail();
			} catch(WrongArgumentException $e) {
				# all good
			}
		}

		public function testIsFalse()
		{
			$this->assertTrue(Assert::isFalse(false));
			
			try {
				Assert::isFalse(true);
				$this->fail();
			} catch(WrongArgumentException $e) {
				# all good
			}
		}
		
		public function testIsNotNull()
		{
			$this->assertTrue(Assert::isNotNull(1));
			
			try {
				Assert::isNotNull(null);
				$this->fail();
			} catch(WrongArgumentException $e) {
				# all good
			}
		}

		public function testIsEqual()
		{
			$this->assertTrue(Assert::isEqual(1, 1));
			
			try {
				Assert::isEqual(1, '1');
				$this->fail();
			} catch(WrongArgumentException $e) {
				# all good
			}
		}

		public function testIsFileExists()
		{
			$this->assertTrue(Assert::isFileExists(__FILE__));
			
			try {
				Assert::isFileExists('noFile');
				$this->fail();
			} catch(FileNotExistsException $e) {
				# all good
			}
		}

		public function testIsImplement()
		{
			$this->assertTrue(
				Assert::isImplement(Cache::me(), 'SingletonInterface')
			);
			
			try {
				Assert::isImplement($this, 'SingletonInterface');
				$this->fail();
			} catch(WrongArgumentException $e) {
				# all good
			}
		}

		public function testIsUnreachable()
		{
			try {
				Assert::isUnreachable();
				$this->fail();
			} catch(UnreachableCodeReachedException $e) {
				# all good
			}
		}
	}
?>