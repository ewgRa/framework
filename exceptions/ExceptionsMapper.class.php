<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	class ExceptionsMapper extends Singleton
	{
		private $map = array(
			'Database'			=> 'DatabaseException',
			'File'				=> 'FileException',
			'Page'				=> 'PageException',
			'Default'			=> 'DefaultException'
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