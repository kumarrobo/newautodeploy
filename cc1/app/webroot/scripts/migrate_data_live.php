<?php
$DIR_PATH = __DIR__;

$date= $argv[1];

if (is_numeric(str_replace(array("-","/"),"",$date))) {
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateShopData_live $date 0 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateShopData_live $date 300000 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateShopData_live $date 600000 > /dev/null 2> /dev/null & echo $!");
    shell_exec("nohup sh " . $DIR_PATH."/migrate.sh migrateShopData_live $date 900000 > /dev/null 2> /dev/null & echo $!");
}
