#!/bin/bash

phpunit $* \
	--bootstrap bootstrap.php \
	--coverage-html \
	/tmp/ewgraFrameworkCodeCoverage AllTestSuite.php
