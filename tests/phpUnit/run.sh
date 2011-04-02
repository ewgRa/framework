#!/bin/bash

phpunit $* \
	--bootstrap bootstrap.php \
	AllTestSuite