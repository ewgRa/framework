<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SingletonTestCase extends FrameworkTestCase
	{
		private $testVar = 'testVar';
		
		public function testIsRealySingleton()
		{
			$this->assertSame(
				MySingletonTest::me()->getTestVariable(),
				null
			);
			
			MySingletonTest::me()->setTestVariable($this->testVar);
			
			$this->assertSame(
				MySingletonTest::me()->getTestVariable(),
				$this->testVar
			);
		}
		
		public function testExtendsFromSingleton()
		{
			MySingletonTest::me()->setTestVariable($this->testVar);
			MySingletonTest2::me()->setTestVariable($this->testVar . rand());
			
			$this->assertNotSame(
				MySingletonTest::me()->getTestVariable(),
				MySingletonTest2::me()->getTestVariable()
			);
		}

		public function testExtendsFromSingletonAsSame()
		{
			MySingletonTest::me()->setTestVariable($this->testVar);
			MySingletonTest3::me()->setTestVariable($this->testVar . rand());
			
			$this->assertSame(
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