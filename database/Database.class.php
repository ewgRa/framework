<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	class Database extends SingletonFactory
	{
		/**
		 * @return BaseLocalizer
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		/**
		 * @return BaseLocalizer
		 */
		public static function factory($realization)
		{
			$method = new ReflectionMethod($realization, 'create');
			
			return
				self::setInstance(__CLASS__, $method->invoke(null));
		}
	}
?>