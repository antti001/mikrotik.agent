#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
LOGFILE="/tmp/mikrotik.agent.log"
TIMESTAMP=`date "+%d.%m %H:%M:%S"`
echo "$TIMESTAMP execute git_upgrade.sh" >> $LOGFILE
#git reset --hard origin/master
#git clean -fxd
cd $DIR
git pull