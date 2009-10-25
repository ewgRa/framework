<?php
	/* $Id$ */
	
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
			} catch(DefaultException $e) {
				return;
			}
			
			$this->fail();
		}

		public function testIsTrue()
		{
			$this->assertTrue(Assert::isTrue(true));
			
			try {
				Assert::isTrue(false);
			} catch(DefaultException $e) {
				return;
			}
			
			$this->fail();
		}

		public function testIsNotNull()
		{
			$this->assertTrue(Assert::isNotNull(1));
			
			try {
				Assert::isNotNull(null);
			} catch(DefaultException $e) {
				return;
			}
			
			$this->fail();
		}

		public function testIsEqual()
		{
			$this->assertTrue(Assert::isEqual(1, 1));
			
			try {
				Assert::isEqual(1, '1');
			} catch(DefaultException $e) {
				return;
			}
			
			$this->fail();
		}

		public function testIsFileExists()
		{
			$this->assertTrue(Assert::isFileExists(__FILE__));
			
			try {
				Assert::isFileExists('noFile');
			} catch(FileException $e) {
				return;
			}
			
			$this->fail();
		}

		public function testIsImplement()
		{
			$this->assertTrue(
				Assert::isImplement(Cache::me(), 'SingletonInterface')
			);
			
			try {
				Assert::isImplement($this, 'SingletonInterface');
			} catch(DefaultException $e) {
				return;
			}
			
			$this->fail();
		}
	}
?>