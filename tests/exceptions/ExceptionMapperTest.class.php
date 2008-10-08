<?php
	/* $Id$ */

	class ExceptionMapperTest extends UnitTestCase
	{
		const EXCEPTION_ALIAS = 'test';
		
		public function testIsSingleton()
		{
			$this->assertTrue(ExceptionsMapper::me() instanceof Singleton);
		}

		public function testCreateHandler()
		{
			ExceptionsMapper::me()->setClassName(
				self::EXCEPTION_ALIAS,
				'MyException'
			);
			
			$this->assertTrue(
				ExceptionsMapper::me()->createException(self::EXCEPTION_ALIAS)
					instanceof MyException
			);

			$this->assertEqual(
				ExceptionsMapper::me()->createException(self::EXCEPTION_ALIAS)->
					setParam('exceptionParam')->
					getParam(),
				'exceptionParam'
			);
		}

		public function testCreateDefaultHandler()
		{
			$this->assertTrue(
				ExceptionsMapper::me()->createException('testDefaultException')
					instanceof DefaultException
			);
		}
	}

	class MyException extends DefaultException
	{
	    public function setParam($param)
		{
			$this->param = $param;
			return $this;
		}

		public function getParam()
		{
			return $this->param;
		}
	}
?>