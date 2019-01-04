<?php

$DIR_PATH = __DIR__;

$date= $argv[1];

if (is_numeric(str_replace(array("-","/"),"",$date))) {
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 0 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 1 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 2 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 3 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 4 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 5 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 6 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 7 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 8 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 9 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 10 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 11 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 12 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 13 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 14 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 15 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 16 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 17 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 18 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 19 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 20 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 21 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 22 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateVaData $date 23 > /dev/null 2> /dev/null & echo $!");
}