#!/bin/bash

ln -s AllTestSuite.class.php AllTestSuite.php
mkdir /tmp/ewgraFrameworkCodeCoverage

phpunit --coverage-html /tmp/ewgraFrameworkCodeCoverage AllTestSuite.php

rm AllTestSuite.php
