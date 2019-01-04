#!/bin/bash
SCRIPTPATH=$(dirname "$0")
cd $SCRIPTPATH
cd ../../config

SITE_NAME=`cat bootstrap.php | grep "SITE_NAME" | awk -F';' '{ print $1 }' | awk -F',' '{ print $2 }' | tr -d "')"`

nohup /usr/bin/wget -O/dev/null $SITE_NAME/crons/update_api_recon/$1/$2/$3/$4  > /dev/null 2>&1 &

cd -