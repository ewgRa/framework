<?php
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

	class SingletonTest extends UnitTestCase
	{
		private $testVar = 'testVar';
		private $constructorArgs = array('constructor', 'arguments');
		
		function testIsRealySingleton()
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
		
		function testExtendsFromImplementsSingleton()
		{
			MySingletonTest::me()->setTestVariable($this->testVar);
			MySingletonTest2::me()->setTestVariable($this->testVar . rand());
			
			$this->assertFalse(
				MySingletonTest::me()->getTestVariable()
				== MySingletonTest2::me()->getTestVariable()
			);
		}
	}
?>