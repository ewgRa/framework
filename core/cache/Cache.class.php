<?php
	/* $Id$ */

	$file = join(
		DIRECTORY_SEPARATOR,
		array(
			dirname(__FILE__), '..' , '..' , 'patterns' , 'Singleton.class.php'
		)
	);
	
	if(!class_exists('Singleton', false) && file_exists($file))
		require_once($file);
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * // FIXME: organize pools for cache
	*/
	final class Cache extends Singleton
	{
		/**
		 * @return BaseCache
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function addPool(BaseCache $pool, $poolAlias = null)
		{
			$this->pools[$poolAlias] = $pool;
			return $this;
		}
		
		public function hasPool($poolAlias)
		{
			return isset($this->pools[$poolAlias]);
		}
		
		/**
		 * @return BaseCache
		 */
		public function getPool($poolAlias = null)
		{
			if($this->hasPool($poolAlias))
				return $this->pools[$poolAlias];
			else
				throw
					ExceptionsMapper::me()->createException('MissingArgument')->
						setMessage('Known nothing about pool ' . $poolAlias);
		}
	}
?>