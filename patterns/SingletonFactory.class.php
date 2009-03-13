<?php
	/* $Id$ */

	$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'SingletonFactoryInterface.class.php';
	
	if(!interface_exists('SingletonFactoryInterface', false) && file_exists($file))
		require_once($file);
	
	$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Singleton.class.php';
	
	if(!class_exists('Singleton', false) && file_exists($file))
		require_once($file);
		
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class SingletonFactory extends Singleton implements SingletonFactoryInterface
	{
	}
?>