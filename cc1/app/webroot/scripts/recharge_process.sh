#!/bin/bash
SCRIPTPATH=$(dirname "$0")
cd $SCRIPTPATH
cd ../../config

SITE_NAME=`cat bootstrap.php | grep "SITE_NAME" | awk -F';' '{ print $1 }' | awk -F',' '{ print $2 }' | tr -d "')"`

/usr/bin/wget -O/dev/null $SITE_NAME/recharges/startTransaction/$1 2>&1 > /dev/null

cd -
