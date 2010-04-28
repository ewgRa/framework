<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/

	require_once(dirname(__FILE__).'/../init.php');
	require_once(dirname(__FILE__).'/FrameworkTestCase.class.php');
	require_once(dirname(__FILE__).'/FrameworkTestSuite.class.php');

	classesAutoloaderInit();
	cacheInit();
	
	$testSuite = $_SERVER['argv'][1];
	class_exists($testSuite);
	
	define(
		'ROOT_SUITE_DIR',
		realpath(dirname(ClassesAutoloader::me()->getFoundClassFile($testSuite)))
	);
		
	$diff = substr(ROOT_SUITE_DIR, strlen(dirname(__FILE__)));
	
	if (!$diff)
		$diff = null;

	define('ROOT_SUITE_CASES_DIR', realpath(CASES_DIR.$diff));
	
	echo PHP_EOL.'ROOT_SUITE_DIR: '.ROOT_SUITE_DIR.PHP_EOL;
	echo 'ROOT_SUITE_CASES_DIR: '.ROOT_SUITE_CASES_DIR.PHP_EOL.PHP_EOL;
?>