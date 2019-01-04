#!/bin/bash

###########################################################################################################################################################
## Usage :  This script will find out whether the required process is running or not. If not it will start the process.
##          It also calculate how many instance of the process need to be run to handle the request flow.
##
###########################################################################################################################################################

## ---- Getting unix timestamp of GMT zone
UTS=`date +%s`

## ---- converting GMT to IST by adding 5hr 30 min
UTS=$(( $UTS + ( 5 * 60 * 60 ) + ( 30 * 60 ) ))

## ---- getting last min from above time
lastmin=$( date -d @$(( $UTS - 60 )) +'%Y-%m-%d %H:%M' )

## ---- getting total count of request handled by existing processes
lcount=$(grep "$lastmin" /tmp/updaterchanges_process.txt  | wc -l)

## ---- getting count of process running for execution
CUR_PROC_CNT=$(($(ps -ef | grep modem_update_getter | wc -l) - 1 ))

## ---- getting expected count of processes that need to run for execution
EXP_PROC_CNT=$(( ( $lcount / 25 ) + 1 ))


## ---- check to start minimum process
if [ $EXP_PROC_CNT -lt 2 ];
then
    EXP_PROC_CNT=2
fi

SCRIPTPATH=$(dirname "$0")

if [ $CUR_PROC_CNT -lt $EXP_PROC_CNT ];
then
  while [ $CUR_PROC_CNT -lt $EXP_PROC_CNT ]
  do
        
        START_PROC=`nohup php $SCRIPTPATH/modem_update_getter.php > /dev/null 2> /dev/null & echo $!`
        CUR_PROC_CNT=$(( $CUR_PROC_CNT + 1 ))
        echo $lastmin" :Increased current process to : $CUR_PROC_CNT"
  done
else
  echo $lastmin" : current process cnt : $CUR_PROC_CNT || expected process cnt : $EXP_PROC_CNT "
fi
