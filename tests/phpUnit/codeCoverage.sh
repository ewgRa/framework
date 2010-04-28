#!/bin/bash

echo "<?php require_once(dirname(__FILE__).'/runTestSuite.php'); ?>" > AllTestSuite.php 

cat AllTestSuite.class.php >> AllTestSuite.php

mkdir /tmp/ewgraFrameworkCodeCoverage

phpunit --coverage-html /tmp/ewgraFrameworkCodeCoverage AllTestSuite.php

rm AllTestSuite.php