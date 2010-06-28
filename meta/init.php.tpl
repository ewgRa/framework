<?php
	define('FRAMEWORK_DIR', dirname(__FILE__).'/..');
	define('META_BUILDER_DIR', dirname(__FILE__));
	
	require_once(FRAMEWORK_DIR . '/core/patterns/SingletonInterface.class.php');
	require_once(FRAMEWORK_DIR . '/core/patterns/Singleton.class.php');
	require_once(FRAMEWORK_DIR . '/ClassesAutoloader.class.php');

	ClassesAutoloader::me()->
		addSearchDirectories(
			array(FRAMEWORK_DIR)
		);
	
	function __autoload($className)
	{
		if (!class_exists('ClassesAutoloader', false))
			return null;
		
		ClassesAutoloader::me()->load($className);
	}
?>