<?php
	error_reporting(E_ALL);
	ini_set('display_errors', true);
	
	define('SIMPLETEST_DIR', '/usr/share/php/simpletest');

	define('FRAMEWORK_DIR', dirname(__FILE__) . '/..');
	define('LIB_DIR', '/var/www/ewgraProjects/lib/trunk');

	define('TMP_DIR', '/tmp/ewgraFrameworkTests');
	define('CACHE_DIR', '/tmp/ewgraFrameworkTests/cache');
	
	if(!file_exists(TMP_DIR))
		mkdir(TMP_DIR, 0777, true);
	
	if(!file_exists(CACHE_DIR))
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

	require_once dirname(__FILE__) . '/FrameworkAllTests.class.php';

	$allTests = new FrameworkAllTests();

	$reporter =
		PHP_SAPI == 'cli'
			? new TextReporter()
			: new HtmlReporter();
		
	$allTests->run($reporter);

	function __autoload($className)
	{
		ClassesAutoloader::me()->load($className);
	}
?>