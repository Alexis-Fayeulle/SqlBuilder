#!/bin/bash

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
phpunit="$SCRIPT_DIR/../../vendor/bin/phpunit"

cd "$SCRIPT_DIR/.."

$phpunit $@
