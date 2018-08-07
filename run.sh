#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"

LOGFILE="/tmp/mikrotik.agent.log"
TIMESTAMP=`date "%d.%m %H:%M:%S"`
echo "$TIMESTAMP execute run.sh $1" >> $LOGFILE

php $DIR/run.php $1
sleep 30
php $DIR/run.php
