<?php
	require_once dirname(__FILE__) . '/FrameworkAllTests.class.php';

	$allTests = new FrameworkAllTests();
	$allTests->run(new HtmlReporter());
?>