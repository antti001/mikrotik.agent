#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
php $DIR/run.php
sleep 30
php $DIR/run.php
