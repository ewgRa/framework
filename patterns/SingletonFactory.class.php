<?php
	/* $Id$ */

	$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'SingletonFactoryInterface.class.php';
	
	if(!interface_exists('SingletonFactoryInterface', false) && file_exists($file))
		require_once($file);
	
	$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Singleton.class.php';
	
	if(!class_exists('Singleton', false) && file_exists($file))
		require_once($file);
		
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * // FIXME: tested?
 	 */
	abstract class SingletonFactory extends Singleton implements SingletonFactoryInterface
	{
	}
?>