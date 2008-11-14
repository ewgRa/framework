<?php
	error_reporting(E_ALL);
	ini_set('display_errors', true);
	
	define('SIMPLETEST_DIR', '/usr/share/php/simpletest');
	define('FRAMEWORK_DIR', '/var/www/ewgraProjects/framework/trunk');
	define('LIB_DIR', '/var/www/ewgraProjects/lib/trunk');
	define('TMP_DIR', '/tmp/ewgraFrameworkTests');
	define('CACHE_DIR', '/tmp/ewgraFrameworkTests/cache');
	
	if(!file_exists(TMP_DIR))
		mkdir(TMP_DIR, 0777, true);
	
	if(!file_exists(TMP_DIR))
		mkdir(CACHE_DIR, 0777, true);
		
	require_once(SIMPLETEST_DIR . '/simpletest.php');
	require_once(SIMPLETEST_DIR . '/unit_tester.php');
	require_once(SIMPLETEST_DIR . '/reporter.php');
	require_once(SIMPLETEST_DIR . '/mock_objects.php');
	
	require_once(FRAMEWORK_DIR . '/core/cache/Cache.class.php');
	require_once(FRAMEWORK_DIR . '/core/cache/FileBasedCache.class.php');
	require_once(FRAMEWORK_DIR . '/ClassesAutoloader.class.php');
	
	ClassesAutoloader::me()->
		setSearchDirectories(array(FRAMEWORK_DIR));

	include_once FRAMEWORK_DIR . '/tests/run.php';

	function __autoload($className)
	{
		ClassesAutoloader::me()->load($className);
	}
?>