<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	class CacheFactory
	{
		/**
		 * @return Cache
		 */
		public static function factory($realization)
		{
			$reflection = new ReflectionMethod($realization, 'create');

			return
				Singleton::setInstance('Cache', $reflection->invoke(null));
		}
		
	}
?>