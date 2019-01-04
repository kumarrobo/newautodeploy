<?php
$DIR_PATH = __DIR__;

shell_exec("sh " . $DIR_PATH."/migrate.sh resetTables");
shell_exec("sh " . $DIR_PATH."/migrate.sh migrateDistributorData");