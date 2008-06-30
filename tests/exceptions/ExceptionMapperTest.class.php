<?php
	/* $Id$ */

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
	
	class ExceptionMapperTest extends UnitTestCase
	{
		const EXCEPTION_ALIAS = 'test';
		
		function testIsSingleton()
		{
			$this->assertTrue(ExceptionsMapper::me() instanceof Singleton);
		}

		function testSetClassName()
		{
			ExceptionsMapper::me()->setClassName(self::EXCEPTION_ALIAS, 'MyException');
			
			$this->assertEqual(
				ExceptionsMapper::me()->getClassName(self::EXCEPTION_ALIAS),
				'MyException'
			);
		}
		
		function testCreateHandler()
		{
			ExceptionsMapper::me()->setClassName(self::EXCEPTION_ALIAS, 'MyException');
			
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

		function testCreateDefaultHandler()
		{
			$this->assertTrue(
				ExceptionsMapper::me()->createException('testDefaultException')
					instanceof DefaultException
			);
		}
	}
?>