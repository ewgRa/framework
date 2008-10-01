<?php
	/* $Id$ */

	class ExceptionsMapper extends Singleton
	{
		private $map = array(
			'Database'	=> 'DatabaseException',
			'File'		=> 'FileException',
			'Page'		=> 'PageException'
		);
		
		private static $instance = null;
		
		/**
		 * @return ExceptionsMapper
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		/**
		 * @return ExceptionsMapper
		 */
		public function setClassName($exceptionAlias, $className)
		{
			$this->map[$exceptionAlias] = $className;
			return $this;
		}

		public function getClassName($exceptionAlias)
		{
			return
				isset($this->map[$exceptionAlias])
					? $this->map[$exceptionAlias]
					: null;
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
				$className = $this->getClassName($exceptionAlias);
			
			return new $className($message, $code);
		}
	}
?>