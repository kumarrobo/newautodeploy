<?php

$DIR_PATH = __DIR__;

$date= $argv[1];

shell_exec("nohup sh " . $DIR_PATH."/migrate.sh removeUnmatchedData $date 0 > /dev/null 2> /dev/null & echo $!");

