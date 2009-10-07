#!/bin/bash

TEST_SUITE=$1

[ "$TEST_SUITE" = "" ] && TEST_SUITE="AllTestSuite"

phpunit $TEST_SUITE runTestSuite.php