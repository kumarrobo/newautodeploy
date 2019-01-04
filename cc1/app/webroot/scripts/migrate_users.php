<?php
$DIR_PATH = __DIR__;

shell_exec("sh " . $DIR_PATH."/migrate.sh removeMembers");
shell_exec("sh " . $DIR_PATH."/migrate.sh transferWallets");
shell_exec("sh " . $DIR_PATH."/migrate.sh moveIntoGroups");
shell_exec("sh " . $DIR_PATH."/migrate.sh moveSalesman");
