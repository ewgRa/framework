<?php
	class ExceptionsMapper extends Singleton
	{
		private $map = array(
			'Database' => 'DatabaseException',
			'File' => 'FileException'
		);
		
		private static $instance = null;
		
		/**
		 * @return ExceptionsMapper
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__, self::$instance);
		}

		public function setClassName($exceptionAlias, $className)
		{
			$this->map[$exceptionAlias] = $className;
			return $this;
		}

		public function getClassName($exceptionAlias)
		{
			if(isset($this->map[$exceptionAlias]))
				return $this->map[$exceptionAlias];
				
			return null;
		}
		
		/**
		 * @return DefaultException
		 */
		public function createException(
			$exceptionAlias,
			$code = 0,
			$message = null
		)
		{
			$className = 'DefaultException';
			
			if($this->getClassName($exceptionAlias))
			{
				$className = $this->getClassName($exceptionAlias);	
			}

			$result = new $className($message, $code);
			
			$trace = array_shift(debug_backtrace());
			$result->setLine($trace['line']);
			$result->setFile($trace['file']);
			
			return $result;
		}
	}
?>