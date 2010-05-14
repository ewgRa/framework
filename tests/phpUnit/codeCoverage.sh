#!/bin/bash

phpunit \
	--bootstrap runTestSuite.php \
	--coverage-html \
	/tmp/ewgraFrameworkCodeCoverage AllTestSuite.class.php
