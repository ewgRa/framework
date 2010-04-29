<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SingletonTestCase extends FrameworkTestCase
	{
		public function setUp()
		{
			$this->dropInstances();
		}
		
		public function tearDown()
		{
			$this->dropInstances();
		}
		
		public function testIsRealySingleton()
		{
			$testVar = rand();
			
			$this->assertSame(
				MySingletonTest::me()->getTestVariable(),
				null
			);
			
			MySingletonTest::me()->setTestVariable($testVar);
			
			$this->assertSame(
				MySingletonTest::me()->getTestVariable(),
				$testVar
			);
		}
		
		public function testExtendsFromSingleton()
		{
			$testVar = rand();
			
			MySingletonTest::me()->setTestVariable($testVar);
			MySingletonTest2::me()->setTestVariable($testVar.rand());
			
			$this->assertNotSame(
				MySingletonTest::me()->getTestVariable(),
				MySingletonTest2::me()->getTestVariable()
			);
		}
		
		private function dropInstances()
		{
			Singleton::dropInstance('MySingletonTest');
			Singleton::dropInstance('MySingletonTest2');
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
	
	final class MySingletonTest2 extends MySingletonTest
	{
		/**
		 * @return MySingletonTest2
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
	}
?>