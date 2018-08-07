#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"

echo "$(date) execute run.php" >> /tmp/mikrotik.agent.txt

php $DIR/run.php
sleep 30
php $DIR/run.php
