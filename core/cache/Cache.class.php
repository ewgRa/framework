<?php
	/* $Id$ */

	$file = join(
		DIRECTORY_SEPARATOR,
		array(
			dirname(__FILE__), '..' , '..' , 'patterns' , 'SingletonFactory.class.php'
		)
	);
	
	if(!class_exists('SingletonFactory', false) && file_exists($file))
		require_once($file);
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * // FIXME: organize pools for cache
	*/
	final class Cache extends SingletonFactory
	{
		/**
		 * @return BaseCache
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		/**
		 * @return BaseCache
		 */
		public static function factory($realization)
		{
			$method = new ReflectionMethod($realization, 'create');
			
			return
				self::setInstance(__CLASS__, $method->invoke(null));
		}
	}
?>