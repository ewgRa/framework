<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Session extends SingletonFactory
	{
		/**
		 * @return BaseSession
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		/**
		 * @return BaseSession
		 */
		public static function factory($realization)
		{
			$method = new ReflectionMethod($realization, 'create');
			
			return self::setInstance(__CLASS__, $method->invoke(null));
		}
	}
?>