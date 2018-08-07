#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"

LOGFILE="/tmp/mikrotik.agent.log"
TIMESTAMP=`date "%d.%m %H:%M:%S"`
echo "$TIMESTAMP execute run.php" >> $LOGFILE

php $DIR/run.php
sleep 30
php $DIR/run.php
