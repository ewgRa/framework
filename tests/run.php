<?php
	/* $Id$ */

	require_once dirname(__FILE__) . '/FrameworkAllTests.class.php';

	$allTests = new FrameworkAllTests();

	$reporter =
		PHP_SAPI == 'cli'
			? new TextReporter()
			: new HtmlReporter();
		
	$allTests->run($reporter);
?>