<?php
	/* $Id$ */

	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', true);
	
	define('PROJECT', 'ewgraFrameworkTests');
	define('FRAMEWORK_DIR', dirname(__FILE__) . '/..');

	define('CASES_DIR', FRAMEWORK_DIR.'/tests/cases');
	define('TMP_DIR', '/tmp/ewgraFrameworkTests');
	define('CACHE_DIR', '/tmp/ewgraFrameworkTests/cache');
	
	if(!file_exists(TMP_DIR))
		mkdir(TMP_DIR, 0777, true);
	
	if(!file_exists(CACHE_DIR))
		mkdir(CACHE_DIR, 0777, true);

	function classesAutoloaderInit($testClassesDir)
	{
		require_once(FRAMEWORK_DIR . '/patterns/SingletonInterface.class.php');
		require_once(FRAMEWORK_DIR . '/patterns/Singleton.class.php');
		require_once(FRAMEWORK_DIR . '/ClassesAutoloader.class.php');

		ClassesAutoloader::me()->
			addSearchDirectories(array($testClassesDir, FRAMEWORK_DIR));
		
		$key = CACHE_DIR.'/'.PROJECT.'-classesAutoloader-'.md5(__FILE__);
		
		$foundClasses =
			file_exists($key)
				? file_get_contents($key)
				: null;
		
		ClassesAutoloader::me()->setFoundClasses(
			$foundClasses
				? unserialize($foundClasses)
				: array()
		);
		
		register_shutdown_function('storeAutoloaderMap', $key);
	}
	
	function storeAutoloaderMap($key)
	{
		if (ClassesAutoloader::me()->isClassMapChanged()) {
			file_put_contents(
				$key,
				serialize(ClassesAutoloader::me()->getFoundClasses())
			);
		}
	}
	
	function __autoload($className)
	{
		if (!class_exists('ClassesAutoloader', false))
			return null;
		
		ClassesAutoloader::me()->load($className);
	}
?>