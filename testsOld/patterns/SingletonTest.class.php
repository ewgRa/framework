<?php
	/* $Id$ */

	class SingletonTest extends UnitTestCase
	{
		private $testVar = 'testVar';
		
		public function testIsRealySingleton()
		{
			$this->assertEqual(
				MySingletonTest::me()->getTestVariable(),
				null
			);
			
			MySingletonTest::me()->setTestVariable($this->testVar);
			
			$this->assertEqual(
				MySingletonTest::me()->getTestVariable(),
				$this->testVar
			);
		}
		
		public function testExtendsFromSingleton()
		{
			MySingletonTest::me()->setTestVariable($this->testVar);
			MySingletonTest2::me()->setTestVariable($this->testVar . rand());
			
			$this->assertNotEqual(
				MySingletonTest::me()->getTestVariable(),
				MySingletonTest2::me()->getTestVariable()
			);
		}

		public function testExtendsFromSingletonAsSame()
		{
			MySingletonTest::me()->setTestVariable($this->testVar);
			MySingletonTest3::me()->setTestVariable($this->testVar . rand());
			
			$this->assertEqual(
				MySingletonTest::me()->getTestVariable(),
				MySingletonTest3::me()->getTestVariable()
			);
		}
	}

	class MySingletonTest extends Singleton
	{
		private $testVariable = null;
		
		/**
		 * @return MySingletonTest
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		/**
		 * @return MySingletonTest
		 */
		public function setTestVariable($variable)
		{
			$this->testVariable = $variable;
			return $this;
		}

		public function getTestVariable()
		{
			return $this->testVariable;
		}

	}
	
	class MySingletonTest2 extends MySingletonTest
	{
		/**
		 * @return MySingletonTest2
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
	}

	class MySingletonTest3 extends MySingletonTest
	{
		/**
		 * @return MySingletonTest3
		 */
		public static function me()
		{
			return parent::getInstance('MySingletonTest');
		}
	}
?>