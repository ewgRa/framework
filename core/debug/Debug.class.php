<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Debug extends SingletonFactory
	{
		/**
		 * @return BaseDebug
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		/**
		 * @return BaseDebug
		 */
		public static function factory($realization)
		{
			$method = new ReflectionMethod($realization, 'create');
			
			return self::setInstance(__CLASS__, $method->invoke(null));
		}
	}
?>