<?php
	/* $Id$ */

	$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'CacheInterface.class.php';
	
	if(!interface_exists('CacheInterface', false) && file_exists($file))
		require_once($file);
	
	$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'CacheTicket.class.php';
		
	if(!class_exists('CacheTicket', false) && file_exists($file))
		require_once($file);
	
	$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . '../Assert.class.php';
		
	if(!class_exists('Assert', false) && file_exists($file))
		require_once($file);
		
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	abstract class BaseCache implements CacheInterface
	{
		const FILE_PERMISSIONS = 0775;
		const DIR_PERMISSIONS = 0775;
		
		private $isDisabled	= false;
		private $isExpired 	= true;

		/**
		 * @return BaseCache
		 */
		public function disable()
		{
			$this->isDisabled = true;
			return $this;
		}
		
		/**
		 * @return BaseCache
		 */
		public function enable()
		{
			$this->isDisabled = false;
			return $this;
		}
		
		public function isDisabled()
		{
			return $this->isDisabled;
		}
	}
?>