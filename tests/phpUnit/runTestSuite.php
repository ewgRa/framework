<?php
	/* $Id$ */

	require_once(dirname(__FILE__).'/../init.php');
	require_once(dirname(__FILE__).'/FrameworkTestCase.class.php');
	require_once(dirname(__FILE__).'/FrameworkTestSuite.class.php');

	classesAutoloaderInit(dirname(__FILE__));
	
	$testSuite = $_SERVER['argv'][1];
	class_exists($testSuite);
	
	define(
		'ROOT_SUITE_DIR',
		realpath(dirname(ClassesAutoloader::me()->getFoundClassFile($testSuite)))
	);
		
	$diff = substr(ROOT_SUITE_DIR, strlen(dirname(__FILE__)));
	
	if (!$diff)
		$diff = null;

	define('ROOT_SUITE_CASES_DIR', CASES_DIR.$diff);
?>